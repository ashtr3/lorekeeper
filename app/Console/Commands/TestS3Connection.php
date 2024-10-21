<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestS3Connection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-s3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs multiple commands to test the S3 connection.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $testFileName = 'text-file-'.now()->timestamp.'.txt';
        $content = 'This is test file content.';

        try {
            
            // Upload the test file
            Storage::disk('s3')->put($testFileName, $content);

            // Check if the file exists
            if (Storage::disk('s3')->exists($testFileName)) {
                $this->info('File uploaded successfully!');

                // Remove the test file
                if (Storage::delete($testFileName)) {
                    $this->info('File deleted successfully!');
                } else {
                    $this->error('Failed to delete the file.');
                }
            } else {
                $this->error('Failed to upload the file.');
            }
        } catch (Exception $e) {
            $this->error('Error: '.$e->getMessage());
        }
    }
}
