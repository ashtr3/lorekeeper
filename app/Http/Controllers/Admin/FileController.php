<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller {
    /**
     * Shows the files index.
     *
     * @param string $folder
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex($folder = null) {
        $filesDirectory = 'files';

        // Create the files directory if it doesn't already exist.
        if (!Storage::exists($filesDirectory)) {
            // Create the directory.
            if (!Storage::makeDirectory($filesDirectory)) {
                $this->abort(500);
                return false;
            }
        }
        if ($folder && !Storage::exists("$filesDirectory/$folder")) {
            abort(404);
        }

        $folderDirectory = $filesDirectory.($folder ? "/$folder" : '');
        $fileList = [];

        if (Storage::exists($folderDirectory)) {
            $allFiles = Storage::files($folderDirectory);
            foreach ($allFiles as $file) {
                $fileList[] = basename($file);
            }
        }

        $folderList = Storage::allDirectories($filesDirectory);
        $folders = array_map(function ($dir) use ($filesDirectory) {
            return str_replace($filesDirectory.'/', '', $dir);
        }, array_filter($folderList, function ($dir) use ($filesDirectory) {
            return $dir !== $filesDirectory;
        }));

        return view('admin.files.index', [
            'folder'  => $folder,
            'folders' => $folders,
            'files'   => $fileList,
        ]);
    }

    /**
     * Creates a new directory in the files directory.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateFolder(Request $request) {
        $request->validate(['name' => 'required|alpha_dash']);
        $directoryPath = 'files/'.$request->get('name');

        if (Storage::makeDirectory($directoryPath)) {
            flash('Folder created successfully.')->success();
        } else {
            flash('Failed to create folder.')->error();
        }

        return redirect()->back();
    }

    /**
     * Moves a file in the files directory.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postMoveFile(Request $request) {
        $request->validate(['destination' => 'required']);
        $oldDir = $request->get('folder');
        $newDir = $request->get('destination');
        $filename = $request->get('filename');

        $oldPath = $oldDir ? "files/$oldDir/$filename" : "files/$filename";
        $newPath = $newDir != 'root' ? "files/$newDir/$filename" : "files/$filename";

        if (Storage::move($oldPath, $newPath)) {
            flash('File moved successfully.')->success();
        } else {
            flash('Failed to move file.')->error();
        }

        return redirect()->back();
    }

    /**
     * Renames a file in the files directory.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRenameFile(Request $request) {
        $request->validate(['name' => 'required|regex:/^[a-z0-9\._-]+$/i']);
        $dir = $request->get('folder');
        $oldName = $request->get('filename');
        $newName = $request->get('name');

        $oldPath = $dir ? "files/$dir/$oldName" : "files/$oldName";
        $newPath = $dir ? "files/$dir/$newName" : "files/$newName";

        if (Storage::move($oldPath, $newPath)) {
            flash('File renamed successfully.')->success();
        } else {
            flash('Failed to rename file.')->error();
        }

        return redirect()->back();
    }

    /**
     * Deletes a file in the files directory.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteFile(Request $request) {
        $request->validate(['filename' => 'required']);
        $dir = $request->get('folder');
        $name = $request->get('filename');
        $filePath = $dir ? "files/$dir/$name" : "files/$name";

        if (Storage::delete($filePath)) {
            flash('File deleted successfully.')->success();
        } else {
            flash('Failed to delete file.')->error();
        }

        return redirect()->back();
    }

    /**
     * Uploads a file to the files directory.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUploadFile(Request $request) {
        $request->validate(['files.*' => 'file|required']);
        $dir = $request->get('folder');
        $files = $request->file('files');

        foreach ($files as $file) {
            $filename = $file->getClientOriginalName();
            $filePath = $dir ? "files/$dir/$filename" : "files/$filename";
            $content = file_get_contents($file);

            if (Storage::put($filePath, $content)) {
                flash('File uploaded successfully.')->success();
            } else {
                flash('Failed to upload file.')->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Renames a directory in the files directory.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRenameFolder(Request $request) {
        $request->validate(['name' => 'required|regex:/^[a-z0-9\._-]+$/i']);
        $oldDir = $request->get('folder');
        $newDir = $request->get('name');

        $oldPath = "files/$oldDir";
        $newPath = "files/$newDir";

        if (Storage::move($oldPath, $newPath)) {
            flash('Folder renamed successfully.')->success();
        } else {
            flash('Failed to rename folder.')->error();
        }

        return redirect()->to('admin/files/'.$newDir);
    }

    /**
     * Deletes a directory in the files directory.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteFolder(Request $request) {
        $request->validate(['folder' => 'required']);
        $folder = $request->get('folder');
        $directory = "files/$folder";

        if (Storage::deleteDirectory($directory)) {
            flash('Folder deleted successfully.')->success();
        } else {
            flash('Failed to delete folder.')->error();
        }

        return redirect()->to('admin/files');
    }

    /**
     * Shows the site images index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSiteImages() {
        return view('admin.files.images', [
            'images' => config('lorekeeper.image_files'),
        ]);
    }

    /**
     * Uploads a site image file.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUploadImage(Request $request) {
        $request->validate(['file' => 'required|file']);
        $file = $request->file('file');
        $key = $request->get('key');
        $filename = config('lorekeeper.image_files.'.$key)['filename'];
        $content = file_get_contents($file);

        if (Storage::put("images/$filename", $content)) {
            flash('Image uploaded successfully.')->success();
        } else {
            flash('Failed to upload image.')->error();
        }

        return redirect()->back();
    }

    /**
     * Uploads a custom site CSS file.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUploadCss(Request $request) {
        $request->validate(['file' => 'required|file']);
        $file = $request->file('file');
        $filename = 'css/'.$file->getClientOriginalName();
        $content = file_get_contents($file);

        if (Storage::put($filename, $content)) {
            flash('CSS file uploaded successfully.')->success();
        } else {
            flash('Failed to upload CSS file.')->error();
        }

        return redirect()->back();
    }
}
