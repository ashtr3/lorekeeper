<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileManager extends Service {
    /*
    |--------------------------------------------------------------------------
    | File Manager
    |--------------------------------------------------------------------------
    |
    | Handles uploading and manipulation of files.
    |
    */

    /**
     * Get the Storage disk instance.
     * 
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function disk(): \Illuminate\Contracts\Filesystem\Filesystem
    {
        return Storage::disk('files');
    }

    /**
     * Creates a directory.
     *
     * @param string $dir
     *
     * @return bool
     */
    public function createDirectory($dir) {
        if ($this->disk()->exists($dir)) {
            $this->setError('error', 'Folder already exists.');

            return false;
        }
        // Create the directory.
        $this->disk()->makeDirectory($dir);

        return true;
    }

    /**
     * Deletes a directory if it exists and doesn't contain files.
     *
     * @param string $dir
     *
     * @return bool
     */
    public function deleteDirectory($dir) {
        if ($this->disk()->exists($dir)) {
            $this->setError('error', 'Directory does not exist.');

            return false;
        }
        if (count($this->disk()->files($dir)) > 0) {
            $this->setError('error', 'Cannot delete a folder that contains files.');

            return false;
        }
        $this->disk()->deleteDirectory($dir);

        return true;
    }

    /**
     * Renames a directory.
     *
     * @param string $dir
     * @param string $oldName
     * @param string $newName
     *
     * @return bool
     */
    public function renameDirectory($dir, $oldName, $newName) {
        $oldPath = $dir ? $dir.'/'.$oldName : $oldName;
        $newPath = $dir ? $dir.'/'.$newName : $newName;

        if (!$this->disk()->exists($oldPath)) {
            $this->setError('error', 'Directory does not exist.');

            return false;
        }
        if (count($this->disk()->files($oldPath)) > 0) {
            $this->setError('error', 'Cannot rename a folder that contains files.');

            return false;
        }

        $this->disk()->makeDirectory($newPath);
        $this->disk()->deleteDirectory($oldPath);

        return true;
    }

    /**
     * Uploads a file.
     *
     * @param array  $file
     * @param string $dir
     * @param string $name
     * @param bool   $isFileManager
     *
     * @return bool
     */
    public function uploadFile($file, $dir, $name, $isFileManager = true) {
        $disk = $isFileManager ? $this->disk() : $this->imageService()->disk();
        $disk->putFileAs($dir ?: '', $file, $name);
        
        return true;
    }

    /**
     * Uploads a custom CSS file.
     *
     * @param array $file
     *
     * @return bool
     */
    public function uploadCss($file) {
        File::move($file, public_path().'/css/custom.css');
        chmod(public_path().'/css/custom.css', 0755);

        return true;
    }

    /**
     * Deletes a file.
     *
     * @param string $path
     *
     * @return bool
     */
    public function deleteFile($path) {
        if (!$this->disk()->exists($path)) {
            $this->setError('error', 'File does not exist.');

            return false;
        }
        $this->disk()->delete($path);

        return true;
    }

    /**
     * Moves a file.
     *
     * @param string $oldDir
     * @param string $newDir
     * @param string $name
     *
     * @return bool
     */
    public function moveFile($oldDir, $newDir, $name) {
        $from = $oldDir ? $oldDir.'/'.$name : $name;
        $to = $newDir ? $newDir.'/'.$name : $name;

        if (!$this->disk()->exists($from)) {
            $this->setError('error', 'File does not exist.');

            return false;
        }
        $this->disk()->move($from, $to);

        return true;
    }

    /**
     * Renames a file.
     *
     * @param string $dir
     * @param string $oldName
     * @param string $newName
     *
     * @return bool
     */
    public function renameFile($dir, $oldName, $newName) {
        $from = $dir ? $dir.'/'.$oldName : $oldName;
        $to = $dir ? $dir.'/'.$newName : $newName;

        if (!$this->disk()->exists($from)) {
            $this->setError('error', 'File does not exist.');

            return false;
        }
        $this->disk()->move($from, $to);

        return true;
    }
}
