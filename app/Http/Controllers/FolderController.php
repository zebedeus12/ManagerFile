<?php

// app/Http/Controllers/FolderController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;

class FolderController extends Controller
{
    public function showForm($parentId = null)
    {
        $folder = null;
        if ($parentId) {
            $folder = Folder::find($parentId);
        }

        return view('folder.form', compact('folder'));
    }


    public function store(Request $request, $parentId = null)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'accessibility' => 'required|in:public,private',
        ]);

        // Membuat folder baru
        $folder = new Folder();
        $folder->name = $request->input('folder_name');
        $folder->accessibility = $request->input('accessibility');
        $folder->parent_id = $parentId; // Jika parentId ada, ini adalah sub-folder
        $folder->save();

        // Mengarahkan ke halaman folder induk atau file manager utama
        if ($parentId) {
            return redirect()->route('folder.show', $parentId)->with('success', 'Sub-folder berhasil dibuat');
        }

        return redirect()->route('file.index')->with('success', 'Folder berhasil dibuat');
    }

    public function show(Folder $folder)
    {
        // Mengambil semua sub-folder dan file yang ada dalam folder
        $subFolders = $folder->subFolders; // Mendapatkan sub-folder dari relasi
        $files = $folder->files; // Mendapatkan file dari relasi

        return view('folder.show', compact('folder', 'subFolders', 'files'));
    }

}