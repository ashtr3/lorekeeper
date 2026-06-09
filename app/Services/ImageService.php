<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageService {
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
     * 
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function disk(): \Illuminate\Contracts\Filesystem\Filesystem
    {
        return Storage::disk('images');
    }

    /**
     * Stores an image at the given path.
     * 
     * @param string $path
     * @param array  $contents
     * 
     * @return bool
     */
    public function store(string $path, $contents): bool
    {
        return $this->disk()->put($path, $contents);
    }

    /**
     * Stores an image at the given path with a specified file name.
     * 
     * @param string $directory
     * @param array  $file
     * @param string $name
     * 
     * @return string|false
     */
    public function storeFileAs(string $directory, $file, string $name): string|false
    {
        return $this->disk()->putFileAs($directory, $file, $name);
    }

    /**
     * Deletes a file from storage.
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function delete(string $path): bool
    {
        if (!$this->exists($path)) {
            return true;
        }

        return $this->disk()->delete($path);
    }

    /**
     * Move a file within storage.
     * 
     * @param string $from
     * @param string $to
     * 
     * @return bool
     */
    public function move(string $from, string $to): bool
    {
        return $this->disk()->move($from, $to);
    }

    /**
     * Copy a file within storage.
     * 
     * @param string $from
     * @param string $to
     * 
     * @return bool
     */
    public function copy(string $from, string $to): bool
    {
        return $this->disk()->copy($from, $to);
    }

    /**
     * Check if a file exists in storage.
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function exists(string $path): bool
    {
        return $this->disk()->exists($path);
    }

    /**
     * Get the public URL for a stored file.
     * 
     * @param string $path
     * 
     * @return ?string
     */
    public function url(string $path): ?string
    {
        return $this->disk()->url($path);
    }

    /**
     * Get file contents.
     * 
     * @param string $path
     * 
     * @return ?string
     */
    public function get(string $path): ?string
    {
        if (!$this->exists($path)) {
            return null;
        }

        return $this->disk()->get($path);
    }

    /**
     * Create a directory if it doesn't exist.
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function makeDirectory(string $path): bool
    {
        return $this->disk()->makeDirectory($path);
    }

    /**
     * Delete a directory.
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function deleteDirectory(string $path): bool
    {
        return $this->disk()->deleteDirectory($path);
    }

    /**
     * Load a file from storage into an Intervention Image instance.
     * 
     * @param string $path
     * 
     * @return ?\Intervention\Image\Image
     */
    public function makeImage(string $path): ?\Intervention\Image\Image
    {
        $content = $this->get($path);
        if ($content === null) {
            return null;
        }

        return Image::make($content);
    }

    /**
     * Save an Intervention Image instance to storage.
     * 
     * @param \Intervention\Image\Image $image
     * @param string  $path
     * @param int     $quality
     * @param ?string $format
     * 
     * @return bool
     */
    public function saveProcessedImage(\Intervention\Image\Image $image, string $path, int $quality = 100, ?string $format = null): bool
    {
        $encoded = $format
            ? (string) $image->encode($format, $quality)
            : (string) $image->encode(null, $quality);

        return $this->store($path, $encoded);
    }
}