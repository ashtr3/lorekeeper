<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageService {
    /**
     * Stores an image at the given path.
     *
     * @param array $contents
     */
    public function store(string $path, $contents): bool {
        return $this->disk()->put($path, $contents);
    }

    /**
     * Stores an image at the given path with a specified file name.
     *
     * @param array $file
     */
    public function storeFileAs(string $directory, $file, string $name): string|false {
        return $this->disk()->putFileAs($directory, $file, $name);
    }

    /**
     * Deletes a file from storage.
     */
    public function delete(string $path): bool {
        if (!$this->exists($path)) {
            return true;
        }

        return $this->disk()->delete($path);
    }

    /**
     * Move a file within storage.
     */
    public function move(string $from, string $to): bool {
        return $this->disk()->move($from, $to);
    }

    /**
     * Copy a file within storage.
     */
    public function copy(string $from, string $to): bool {
        return $this->disk()->copy($from, $to);
    }

    /**
     * Check if a file exists in storage.
     */
    public function exists(string $path): bool {
        return $this->disk()->exists($path);
    }

    /**
     * Get the public URL for a stored file.
     */
    public function url(string $path): ?string {
        return $this->disk()->url($path);
    }

    /**
     * Get file contents.
     */
    public function get(string $path): ?string {
        if (!$this->exists($path)) {
            return null;
        }

        return $this->disk()->get($path);
    }

    /**
     * Create a directory if it doesn't exist.
     */
    public function makeDirectory(string $path): bool {
        return $this->disk()->makeDirectory($path);
    }

    /**
     * Delete a directory.
     */
    public function deleteDirectory(string $path): bool {
        return $this->disk()->deleteDirectory($path);
    }

    /**
     * Load a file from storage into an Intervention Image instance.
     */
    public function makeImage(string $path): ?\Intervention\Image\Image {
        $content = $this->get($path);
        if ($content === null) {
            return null;
        }

        return Image::make($content);
    }

    /**
     * Save an Intervention Image instance to storage.
     */
    public function saveProcessedImage(\Intervention\Image\Image $image, string $path, int $quality = 100, ?string $format = null): bool {
        $encoded = $format
            ? (string) $image->encode($format, $quality)
            : (string) $image->encode(null, $quality);

        return $this->store($path, $encoded);
    }
    /*
    |--------------------------------------------------------------------------
    | Image Service
    |--------------------------------------------------------------------------
    |
    | Handles the creation, processing, and storage of images.
    |
    */

    /**
     * Get the Storage disk instance.
     */
    protected function disk(): \Illuminate\Contracts\Filesystem\Filesystem {
        return Storage::disk('images');
    }
}
