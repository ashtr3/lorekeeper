<?php

namespace App\Console\Commands;

use DOMDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;

class ScrapeCharacters extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'characters:scrape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $auth_code;

    /**
     * Execute the console command.
     */
    public function handle() {
        // Start the local server to listen for the redirect
        $process = new Process(['php', '-S', 'localhost:80', '-t', public_path()]);
        $process->start();

        // Open the authorization URL
        $this->openAuthorizationUrl();

        // Wait for the authorization code
        while ($this->auth_code === null) {
            $this->checkForAuthCode();
            sleep(1);
        }

        $process->stop();

        // Get the access token
        $access_token = $this->getAccessToken($this->auth_code);

        if ($access_token) {
            $this->info('Access token retrieved.');
            $file_name = $this->ask('Enter the output file name (exclude extension):');
            $username = $this->ask('Enter the DeviantArt username:');

            $allDeviations = [];
            $hasMore = true;
            $offset = 0;

            if ($this->confirm('Do you want to include all folders?')) {
                $this->info('Including all folders.');
                while ($hasMore) {
                    $data = $this->getDeviations($username, null, $access_token, $offset);
                    $allDeviations = array_merge($allDeviations, $data['results']);
                    $hasMore = $data['has_more'];
                    $offset = $data['next_offset'];
                    $this->line('Retrieved '.count($data['results']).' deviations, total: '.count($allDeviations));
                }
                $this->line('Retrieved all deviations.');
            } else {
                $allFolders = [];

                while ($hasMore) {
                    $data = $this->getFolders($username, $access_token, $offset);
                    $allFolders = array_merge($allFolders, $data['results']);
                    $hasMore = $data['has_more']; // Update this condition based on your API response
                    $offset = $data['next_offset']; // Adjust based on the limit set in your API call
                }

                $selectedFolders = [];
                $this->info("\nAvailable folders:");
                foreach ($allFolders as $folder) {
                    $folderIds = $this->promptFolderInclusion($folder);
                    $selectedFolders = array_merge($selectedFolders, $folderIds);
                }

                foreach ($selectedFolders as $folder) {
                    $this->info("Retrieving deviations from folder: {$folder}");
                    $hasMore = true;
                    $offset = 0;

                    while ($hasMore) {
                        $data = $this->getDeviations($username, $folder, $access_token, $offset);
                        $allDeviations = array_merge($allDeviations, $data['results']);
                        $hasMore = $data['has_more'];
                        $offset = $data['next_offset'];
                        $this->line('Retrieved '.count($data['results']).' deviations, total: '.count($allDeviations));
                    }
                    $this->line("Retrieved all deviations from folder: {$folder}");
                }
                $this->line('Retrieved all deviations.');
            }

            $this->info('Retrieving deviation metadata.');
            $metadata = $this->processDeviations($allDeviations, $access_token);
            $this->line('Retrieved all deviation metadata.');

            $this->writeToFile($allDeviations, $metadata, $file_name);
            $this->info("Wrote deviation data to {$file_name}.tsv");
        }
    }

    protected function openAuthorizationUrl() {
        $client_id = env('DEVIANTART_CLIENT_ID');
        $redirect_uri = env('DEVIANTART_REDIRECT_URL');
        $scopes = 'browse';
        $auth_url = "https://www.deviantart.com/oauth2/authorize?response_type=code&client_id={$client_id}&redirect_uri={$redirect_uri}&scope={$scopes}";

        $this->info('Opening authorization URL...');

        $process = new Process(['cmd', '/c', 'start', '', $auth_url]);
        $process->run();
    }

    protected function checkForAuthCode() {
        // Check if the auth_code is available
        if (Cache::has('auth_code')) {
            $this->auth_code = Cache::get('auth_code');
            Cache::forget('auth_code');
        }
    }

    protected function getAccessToken($auth_code) {
        $client_id = env('DEVIANTART_CLIENT_ID');
        $client_secret = env('DEVIANTART_CLIENT_SECRET');
        $redirect_uri = env('DEVIANTART_REDIRECT_URL');

        $response = Http::withOptions(['verify' => false])->asForm()->post('https://www.deviantart.com/oauth2/token', [
            'grant_type'    => 'authorization_code',
            'code'          => $auth_code,
            'redirect_uri'  => $redirect_uri,
            'client_id'     => $client_id,
            'client_secret' => $client_secret,
        ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        } else {
            $this->error("Failed to retrieve access token: {$response->body()}");

            return null;
        }
    }

    protected function promptFolderInclusion($folder) {
        $folderIds = [];
        $folderId = $folder['folderid'];
        $folderName = $folder['name'];

        $this->info("Folder ID: {$folderId}, Folder Name: {$folderName}");
        $include = $this->ask("Include '{$folderName}' in the data copy? (y/n)");

        if (in_array(strtolower($include), ['yes', 'y'])) {
            $folderIds[] = $folderId;

            if ($folder['has_subfolders']) {
                $subfolders = $folder['subfolders'];

                foreach ($subfolders as $subfolder) {
                    $subfolderIds = $this->promptFolderInclusion($subfolder);
                    if ($subfolderIds) {
                        $folderIds = array_merge($folderIds, $subfolderIds);
                    }
                }
            }
        }

        return $folderIds;
    }

    protected function getFolders($username, $access_token, $offset = 0) {
        $url = 'https://www.deviantart.com/api/v1/oauth2/gallery/folders';
        $headers = [
            'Authorization' => "Bearer {$access_token}",
        ];
        $params = [
            'username' => $username,
            'limit'    => 50,
            'offset'   => $offset,
        ];

        $response = Http::withOptions(['verify' => false])->withHeaders($headers)->get($url, $params);

        if ($response->successful()) {
            return $response->json();
        } else {
            return null;
        }
    }

    protected function getDeviations($username, $folder, $access_token, $offset = 0) {
        if ($folder == null) {
            $url = 'https://www.deviantart.com/api/v1/oauth2/gallery/all';
        } else {
            $url = "https://www.deviantart.com/api/v1/oauth2/gallery/{$folder}";
        }
        $headers = [
            'Authorization' => "Bearer {$access_token}",
        ];
        $params = [
            'username' => $username,
            'limit'    => 24,
            'offset'   => $offset,
        ];

        $response = Http::withOptions(['verify' => false])->withHeaders($headers)->get($url, $params);

        if ($response->successful()) {
            return $response->json();
        } else {
            return null;
        }
    }

    protected function processDeviations($deviations, $access_token, $batchSize = 50) {
        $metadata = [];

        for ($i = 0; $i < count($deviations); $i += $batchSize) {
            $batch = array_slice($deviations, $i, $batchSize);
            $batchIds = array_map([$this, 'getDeviationId'], $batch);
            $data = $this->getDeviationMetadata($batchIds, $access_token);

            foreach ($data as $item) {
                $deviationId = $item['deviationid'] ?? null;
                if ($deviationId) {
                    $html = $item['description'] ?? 'No description';
                    $text = $this->parseHtml($html);
                    $metadata[$deviationId] = [
                        'html' => $html,
                        'text' => $text,
                    ];
                }
            }
        }

        return $metadata;
    }

    protected function getDeviationId(array $deviation): ?string {
        return $deviation['deviationid'] ?? null;
    }

    protected function parseHtml(string $html): string {
        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        libxml_clear_errors();

        return $dom->textContent;
    }

    protected function getDeviationMetadata($deviationIds, $access_token) {
        $url = 'https://www.deviantart.com/api/v1/oauth2/deviation/metadata';
        $headers = [
            'Authorization' => "Bearer {$access_token}",
        ];
        $params = [
            'deviationids' => $deviationIds,
        ];

        $response = Http::withOptions(['verify' => false])->withHeaders($headers)->get($url, $params);

        if ($response->successful()) {
            return $response->json()['metadata'];
        } else {
            return null;
        }
    }

    protected function writeToFile($deviations, $metadata, $fileName) {
        $filePath = base_path()."/data/tsv/{$fileName}.tsv";

        $file = fopen($filePath, 'w');
        fputcsv($file, ['ID', 'Name', 'URL', 'Image', 'Text', 'HTML'], "\t");

        foreach ($deviations as $deviation) {
            $deviationId = $deviation['deviationid'] ?? null;

            if ($deviationId != null) {
                $titleData = $this->parseDeviationTitle($deviation['title']);
                $id = $titleData['id'];
                $name = $titleData['name'];

                if ($id == null && $name == null) {
                    continue;
                }

                $url = $deviation['url'] ?? '';
                $image = $deviation['content']['src'] ?? '';

                $description = $metadata[$deviationId] ?? [];
                $text = str_replace(["\t", "\n"], ' ', $description['text'] ?? '');
                $html = str_replace(["\t", "\n"], ' ', $description['html'] ?? '');

                fputcsv($file, [$id, $name, $url, $image, $text, $html], "\t");
            }
        }
        fclose($file);
    }

    protected function parseDeviationTitle($title) {
        $id = null;
        $name = null;

        if (preg_match('/(.+) ([0-9]+)/', $title, $matches)) {
            $id = $matches[2];
            $name = $matches[1];
        } elseif (preg_match('/([0-9]+)/', $title, $matches)) {
            $id = $matches[1];
        }

        return [
            'id'   => $id,
            'name' => $name,
        ];
    }
}
