<?php

namespace App\Http\Controllers;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class FileController extends Controller
{
    // FileController.php

    public function index()
    {
        $folders = Folder::whereNull('parent_id')->get(); // Ambil folder utama
        $files = File::all(); // Ambil semua file di root

        return view('files.index', compact('folders', 'files'));
    }

    public function create($folder = null)
    {
        $folder = $folder ? Folder::findOrFail($folder) : null; // Temukan folder atau null jika root
        return view('files.create', compact('folder'));
    }

    public function store(Request $request, $folder = null)
    {
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $filePath = $uploadedFile->store('uploads', 'public');

            // Simpan metadata file ke database
            $file = new File([
                'name' => $uploadedFile->getClientOriginalName(),
                'size' => $uploadedFile->getSize() / 1024, // dalam KB
                'type' => $uploadedFile->extension(),
                'folder_id' => $folder, // Hubungkan file ke folder jika ada
                'created_by' => auth()->id(),
            ]);
            $file->save();
        }

        // Jika folder null, arahkan ke file index
        if ($folder === null) {
            return redirect()->route('file.index')->with('success', 'File berhasil diunggah.');
        }

        // Jika folder ada, arahkan ke folder tersebut
        return redirect()->route('folder.show', ['folder' => $folder])->with('success', 'File berhasil diunggah.');
    }

    public function show($folderId)
    {
        $folder = Folder::findOrFail($folderId); // Temukan folder berdasarkan ID
        $subFolders = $folder->children; // Subfolder di dalam folder ini
        $files = $folder->files; // File di dalam folder ini

        return view('folder.show', compact('folder', 'subFolders', 'files'));
    }
}