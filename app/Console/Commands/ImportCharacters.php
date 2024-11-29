<?php

namespace App\Console\Commands;

use App\Models\Character\Character;
use App\Models\Character\CharacterCategory;
use App\Models\Character\CharacterFeature;
use App\Models\Character\CharacterImage;
use App\Models\Feature\Feature;
use App\Models\Feature\FeatureCategory;
use App\Models\Rarity;
use App\Models\Species\Species;
use App\Models\Species\Subtype;
use App\Models\User\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ImportCharacters extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'characters:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $user = $this->getAdminUser();
        $categories = $this->createCharacterCategories();
        $rarities = $this->createRarities();
        $species = $this->createSpecies();
        $subtypes = $this->createSubtypes($species);
        $featureCategories = $this->createFeatureCategories();
        $features = $this->createFeatures($featureCategories, $rarities);

        $file_name = $this->ask('Enter the output file name (exclude extension, must be in /data/tsv)');

        $csv = $this->getCSV($file_name);
        while (($row = $this->getRow($csv)) !== false) {
            if (!$this->doesCharacterExist($row[0])) {
                $data = $this->getCharacterData($row, $categories, $rarities, $species, $subtypes, $features);
                $this->createCharacter($data, $user);
            } else {
                $this->info("Character ({$row[0]}) exists.");
            }
        }
        fclose($csv);

        return 0;
    }

    /**********************************************************************************************

        DATA PROCESSING

    **********************************************************************************************/

    protected function createCharacterCategories(): array {
        try {
            $categories = [];
            foreach (config('lorekeeper.character-import.character_categories') as $key => $category) {
                ['code' => $code, 'name' => $name] = $category;
                $category = CharacterCategory::where('name', $name)->where('code', $code)->first();
                if (!$category) {
                    $category = CharacterCategory::create([
                        'code' => $code,
                        'name' => $name,
                    ]);
                } else {
                    $this->line('Character category already exists: '.$name);
                }
                $categories[$key] = $category;
            }

            return $categories;
        } catch (Exception $e) {
            $this->error('Error creating character categories.');
        }
    }

    protected function createRarities(): array {
        try {
            $rarities = [];
            foreach (config('lorekeeper.character-import.rarities') as $key => $rarity) {
                ['name' => $name] = $rarity;
                $rarity = Rarity::where('name', $name)->first();
                if (!$rarity) {
                    $rarity = Rarity::create([
                        'name' => $name,
                    ]);
                } else {
                    $this->line('Rarity already exists: '.$name);
                }
                $rarities[$key] = $rarity;
            }

            return $rarities;
        } catch (Exception $e) {
            $this->error('Error creating rarities.');
        }
    }

    protected function createSpecies(): array {
        try {
            $specieses = [];
            foreach (config('lorekeeper.character-import.species') as $key => $species) {
                ['name' => $name] = $species;
                $species = Species::where('name', $name)->first();
                if (!$species) {
                    $species = Species::create([
                        'name' => $name,
                    ]);
                } else {
                    $this->line('Species already exists: '.$name);
                }
                $specieses[$key] = $species;
            }

            return $specieses;
        } catch (Exception $e) {
            $this->error('Error creating species.');
        }
    }

    protected function createSubtypes($specieses): array {
        try {
            $subtypes = [];
            foreach (config('lorekeeper.character-import.subtypes') as $key => $subtype) {
                ['name' => $name, 'species' => $species] = $subtype;
                $species = $specieses[$species];
                $subtype = Subtype::where('species_id', $species->id)->where('name', $name)->first();
                if (!$subtype) {
                    $subtype = Subtype::create([
                        'species_id' => $species->id,
                        'name'       => $name,
                    ]);
                } else {
                    $this->line('Subtype already exists: '.$name);
                }
                $subtypes[$key] = $subtype;
            }

            return $subtypes;
        } catch (Exception $e) {
            $this->error('Error creating subtypes.');
        }
    }

    protected function createFeatureCategories(): array {
        try {
            $featureCategories = [];
            foreach (config('lorekeeper.character-import.feature_categories') as $key => $featureCategory) {
                ['name' => $name] = $featureCategory;
                $featureCategory = FeatureCategory::where('name', $name)->first();
                if (!$featureCategory) {
                    $featureCategory = FeatureCategory::create([
                        'name' => $name,
                    ]);
                } else {
                    $this->line('Feature category already exists: '.$name);
                }
                $featureCategories[$key] = $featureCategory;
            }

            return $featureCategories;
        } catch (Exception $e) {
            $this->error('Error creating feature categories.');
        }
    }

    protected function createFeatures($featureCategories, $rarities): array {
        try {
            $features = [];
            foreach (config('lorekeeper.character-import.features') as $key => $feature) {
                ['name' => $name, 'rarity' => $rarity, 'category' => $category] = $feature;
                $rarity = $rarities[$rarity];

                if ($category != null) {
                    $category = $featureCategories[$category];
                    $feature = Feature::where('feature_category_id', $category->id)->where('rarity_id', $rarity->id)->where('name', $name)->first();
                } else {
                    $feature = Feature::where('rarity_id', $rarity->id)->where('name', $name)->first();
                }

                if (!$feature) {
                    $feature = Feature::create([
                        'feature_category_id' => $category != null ? $category->id : null,
                        'rarity_id'           => $rarity->id,
                        'name'                => $name,
                    ]);
                } else {
                    $this->line('Feature already exists: '.$name);
                }
                $features[$key] = $feature;
            }

            return $features;
        } catch (Exception $e) {
            $this->error('Error creating features.');
        }
    }

    protected function createCharacter($data, $user) {
        try {
            $character = $this->handleCharacter($data);
            if (!$character) {
                throw new \Exception('Error happened while trying to create character.');
            }

            $features = $this->handleCharacterFeatures($data, $character);
            if (!is_array($features) || $features === false) {
                throw new \Exception('Error happened while trying to create character features.');
            }

            $image = $this->handleCharacterImage($data, $character);
            if (!$image) {
                throw new \Exception('Error happened while trying to create image.');
            }

            $this->handleCharacterDesigner($data, $image);

            $this->cropThumbnail($image);
            $character->character_image_id = $image->id;
            $character->save();

            $this->createLog($user->id, null, null, $data['owner_url'], $character->id, 'Character Created', 'Initial upload', 'character');
            $this->createLog($user->id, null, null, $data['owner_url'], $character->id, 'Character Created', 'Initial upload', 'user');
        } catch (\Exception $e) {
            $this->error('Error creating character.');
        }
    }

    protected function handleCharacter($data): Character {
        try {
            $characterData = array_intersect_key($data, array_flip([
                'character_category_id',
                'rarity_id', 'user_id', 'owner_url',
                'name', 'number', 'slug', 'description',
            ]));
            $characterData['parsed_description'] = parse($data['description']);

            $this->line('Creating character: '.$data['slug'].': '.$data['name']);
            $character = Character::create($characterData);
            $character->profile()->create([]);

            if ($character) {
                $this->line('Created: '.$data['slug'].': '.$data['name']);
            }

            return $character;
        } catch (Exception $e) {
            $this->error('Error creating character.');
        }
    }

    protected function handleCharacterFeatures($data, $character): array {
        try {
            $features = [];
            // Attach features
            foreach ($data['features'] as $feature) {
                $characterFeature = CharacterFeature::create([
                    'character_id' => $character->id,
                    'feature_id'   => $feature->id,
                ]);
                $features[] = $characterFeature->id;
            }

            return $features;
        } catch (Exception $e) {
            $this->error('Error creating character features.');
        }
    }

    protected function handleCharacterImage($data, $character): CharacterImage {
        try {
            $imageData = array_intersect_key($data, array_flip([
                'rarity_id', 'species_id', 'subtype_id',
                'use_cropper', 'x0', 'x1', 'y0', 'y1',
            ]));
            $imageData['description'] = $data['image_description'];
            $imageData['parsed_description'] = parse($imageData['description']);
            $imageData['hash'] = randomString(10);
            $imageData['fullsize_hash'] = randomString(15);
            $imageData['sort'] = 0;
            $imageData['extension'] = (config('lorekeeper.settings.masterlist_image_format') ?? ($data['extension'] ?? $data['image']->getClientOriginalExtension()));
            $imageData['fullsize_extension'] = (config('lorekeeper.settings.masterlist_fullsizes_format') ?? ($data['fullsize_extension'] ?? $data['image']->getClientOriginalExtension()));
            $imageData['character_id'] = $character->id;

            $image = CharacterImage::create($imageData);

            $this->saveImage($data['image'], $image->imageDirectory, $image->imageFileName);

            return $image;
        } catch (Exception $e) {
            $this->error('Error creating character image.');
        }
    }

    protected function handleCharacterDesigner($data, $image) {
        try {
            if ($data['designer_url']) {
                DB::table('character_image_creators')->insert([
                    'character_image_id' => $image->id,
                    'type'               => 'Designer',
                    'url'                => $data['designer_url'],
                    'user_id'            => null,
                ]);
            }
        } catch (Exception $e) {
            $this->error('Error creating character designer.');
        }
    }

    protected function createLog($senderId, $senderUrl, $recipientId, $recipientUrl, $characterId, $type, $data, $logType, $isUpdate = false, $oldData = null, $newData = null) {
        return DB::table($logType == 'character' ? 'character_log' : 'user_character_log')->insert(
            [
                'sender_id'     => $senderId,
                'sender_url'    => $senderUrl,
                'recipient_id'  => $recipientId,
                'recipient_url' => $recipientUrl,
                'character_id'  => $characterId,
                'log'           => $type.($data ? ' ('.$data.')' : ''),
                'log_type'      => $type,
                'data'          => $data,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ] + ($logType == 'character' ? [
                'change_log' => $isUpdate ? json_encode([
                    'old' => $oldData,
                    'new' => $newData,
                ]) : null,
            ] : [])
        );
    }

    /**********************************************************************************************

        DATA READING

    **********************************************************************************************/

    protected function getAdminUser(): User {
        try {
            $setting = DB::table('site_settings')->where('key', 'admin_user')->first();
            if (!$setting) {
                $this->error('Admin user setting not found.');
            }
            $userId = $setting->value;
            if (empty($userId)) {
                $this->error('Admin user ID is not set.');
            }
            $user = User::find($userId);
            if (!$user) {
                $this->error('Admin user not found.');
            }

            return $user;
        } catch (Exception $e) {
            $this->error('Error getting admin user.');
        }
    }

    protected function doesCharacterExist($id): bool {
        try {
            $character = Character::where('number', $id)->first();

            return $character ? true : false;
        } catch (Exception $e) {
            $this->error('Error getting character ID.');
        }
    }

    protected function getUserIfExists($alias): ?User {
        try {
            $character = User::whereHas('aliases', function ($query) use ($alias) {
                $query->where('alias', $alias)->where('site', 'deviantart');
            })->first();

            return $character;
        } catch (Exception $e) {
            $this->error('Error getting user.');
        }
    }

    /**********************************************************************************************

        FILE PROCESSING

    **********************************************************************************************/

    protected function getCSV($file_name) {
        $filePath = base_path()."/data/tsv/{$file_name}.tsv";

        if (!file_exists($filePath)) {
            $this->error("File does not exist: /data/tsv/{$file_name}.tsv");

            return false;
        }

        $file = fopen($filePath, 'r');
        $this->getRow($file);

        return $file;
    }

    protected function getRow($csv) {
        return fgetcsv($csv, 0, "\t");
    }

    protected function downloadImage($url): UploadedFile {
        $client = new Client([
            'verify' => false, // Disable SSL certificate verification
        ]);

        $response = $client->get($url);
        $tempDir = base_path('data/images/temp');
        $tempFilePath = $tempDir.'/'.uniqid('img_', true).'.'.$this->getFileExtension($response);

        file_put_contents($tempFilePath, $response->getBody()->getContents());
        $uploadedFile = new UploadedFile(
            $tempFilePath,
            basename($tempFilePath),
            $response->getHeaderLine('Content-Type'),
            null,
            true
        );

        return $uploadedFile;
    }

    protected function saveImage($image, $dir, $name) {
        $fullDir = public_path($dir);

        if (!file_exists($fullDir)) {
            if (!mkdir($fullDir, 0755, true)) {
                $this->error('Failed to create image directory.');

                return false;
            }
            chmod($fullDir, 0755);
        }

        File::move($image, $fullDir.'/'.$name);
        chmod($fullDir.'/'.$name, 0755);

        return true;
    }

    protected function cropThumbnail($characterImage) {
        try {
            $fullPath = $characterImage->imagePath.'/'.$characterImage->imageFileName;
            $this->info($fullPath);
            $image = Image::make($fullPath);

            $canvas = Image::canvas($image->width(), $image->width());
            $image = $canvas->insert($image, 'center');
            $image->resize(config('lorekeeper.settings.masterlist_thumbnails.width'), config('lorekeeper.settings.masterlist_thumbnails.height'));

            $thumbPath = $characterImage->thumbnailPath.'/'.$characterImage->thumbnailFileName;
            $image->save($thumbPath, 100, config('lorekeeper.settings.masterlist_image_format'));
        } catch (\Exception $e) {
            $this->error('Failed to create thumbnail.');
        }
    }

    /**********************************************************************************************

        PARSING

    **********************************************************************************************/

    protected function getCharacterData($row, $categories, $rarities, $species, $subtypes, $features): array {
        $id = $row[0];
        $name = $row[1];
        $url = $row[2];
        $image = $row[3];
        $info = str_replace("\xC2\xA0", ' ', $row[4]);
        $info_html = str_replace("\xC2\xA0", ' ', $row[5]);

        $data = [];
        $data['name'] = $name;
        $data['number'] = $id;
        $data['description'] = $info_html;

        $owner = $this->getOwner($info);
        $data['user_id'] = $owner instanceof User ? $owner->id : null;
        $data['owner_url'] = is_string($owner) ? $owner : null;

        $designer = $this->getDesigner($info);
        $data['designer_id'] = $designer instanceof User ? $designer->id : null;
        $data['designer_url'] = is_string($designer) ? $designer : null;

        $category = $this->getCharacterCategory($info, $categories);
        $data['slug'] = $this->getSlug($id, $category->code);
        $data['character_category_id'] = $category->id ?: null;

        $data['rarity_id'] = $this->getRarity($info, $rarities)->id ?: null;
        $data['species_id'] = $this->getSpecies($info, $species)->id ?: null;
        $data['subtype_id'] = $this->getSubtype($info, $subtypes)->id ?: null;
        $data['features'] = $this->getFeatures($info, $features);

        $uploadedFile = $this->downloadImage($image);
        $data['image'] = $uploadedFile;
        $data['image_description'] = $url;

        return $data;
    }

    protected function getSlug($number, $categoryCode): string {
        $paddedNum = str_pad($number, 3, '0', STR_PAD_LEFT);

        return "{$categoryCode}-{$paddedNum}";
    }

    protected function getOwner($text): User|string|null {
        $pattern = config('lorekeeper.character-import.patterns.owner');
        if (preg_match($pattern, $text, $matches)) {
            $alias = $matches[1];
            $user = $this->getUserIfExists($alias);
            if ($user) {
                return $user;
            } else {
                return "https://www.deviantart.com/{$alias}";
            }
        } else {
            return null;
        }
    }

    protected function getDesigner($text): User|string|null {
        $pattern = config('lorekeeper.character-import.patterns.designer');
        if (preg_match($pattern, $text, $matches)) {
            $alias = $matches[1];
            $user = $this->getUserIfExists($alias);
            if ($user) {
                return $user;
            } else {
                return "https://www.deviantart.com/{$alias}";
            }
        } else {
            return null;
        }
    }

    protected function getCharacterCategory($text, $models): CharacterCategory|false {
        foreach (config('lorekeeper.character-import.character_categories') as $key => $category) {
            $model = $models[$key];
            $pattern = config('lorekeeper.character-import.patterns')[$category['pattern']];
            if ($pattern && preg_match($pattern, $text, $matches)) {
                return $model;
            }
        }

        $default_key = config('lorekeeper.character-import.defaults.category');

        return $models[$default_key];
    }

    protected function getRarity($text, $models): Rarity|false {
        foreach (config('lorekeeper.character-import.rarities') as $key => $rarity) {
            $model = $models[$key];
            $pattern = config('lorekeeper.character-import.patterns')[$rarity['pattern']];
            if ($pattern && preg_match($pattern, $text, $matches)) {
                return $model;
            }
        }

        $default_key = config('lorekeeper.character-import.defaults.rarity');

        return $models[$default_key];
    }

    protected function getSpecies($text, $models): Species|false {
        foreach (config('lorekeeper.character-import.species') as $key => $species) {
            $model = $models[$key];
            $pattern = config('lorekeeper.character-import.patterns')[$species['pattern']];
            if ($pattern && preg_match($pattern, $text, $matches)) {
                return $model;
            }
        }

        $default_key = config('lorekeeper.character-import.defaults.species');

        return $models[$default_key];
    }

    protected function getSubtype($text, $models): Subtype|false {
        foreach (config('lorekeeper.character-import.subtypes') as $key => $subtype) {
            $model = $models[$key];
            $pattern = config('lorekeeper.character-import.patterns')[$subtype['pattern']];
            if ($pattern && preg_match($pattern, $text, $matches)) {
                return $model;
            }
        }

        $default_key = config('lorekeeper.character-import.defaults.subtype');

        return $models[$default_key];
    }

    protected function getFeatures($text, $models): array {
        $features = [];
        foreach (config('lorekeeper.character-import.features') as $key => $feature) {
            $model = $models[$key];
            $pattern = config('lorekeeper.character-import.patterns')[$feature['pattern']];
            if ($pattern && preg_match($pattern, $text, $matches)) {
                $features[] = $model;
            }
        }

        return $features;
    }

    private function getFileExtension($response): string {
        // Retrieve MIME type from the response headers
        $mimeType = $response->getHeaderLine('Content-Type');

        // Map MIME types to file extensions
        $mimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            // Add other MIME types as needed
        ];

        // Default to 'jpg' if MIME type is not recognized
        return $mimeTypes[$mimeType] ?? 'jpg';
    }
}
