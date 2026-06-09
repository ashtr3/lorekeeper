<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CopyDefaultImages extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy-default-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copies default images (as defined in the image_files config file) from the data/images directory to the images storage disk.';

    /**
     * Create a new command instance.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->info('***********************');
        $this->info('* COPY DEFAULT IMAGES *');
        $this->info('***********************'."\n");

        $images = config('lorekeeper.image_files');
        $sourceDir = base_path().'/data/images/';
        $disk = Storage::disk('images');

        foreach ($images as $image) {
            $this->line('Copying image: '.$image['filename']."\n");
            $content = file_get_contents($sourceDir.$image['filename']);
            $disk->put($image['filename'], $content);
        }
        $this->line('Done!');
    }
}
