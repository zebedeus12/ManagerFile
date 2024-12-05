<?php

namespace App\Http\Controllers;
use App\Models\Media;
use App\Models\MediaFolder;
use Illuminate\Http\Request;

class MediaFolderController extends Controller
{
    public function index()
    {
        // Ambil semua media
        $mediaItems = Media::all();
        // Ambil semua folder
        $folders = MediaFolder::all();

        return view('media.index', compact('mediaItems', 'folders'));
    }
    public function create($parentId = null)
    {
        // Menampilkan form untuk membuat folder
        return view('media.createFolder', compact('parentId'));
    }

    public function store(Request $request, $parentId = null)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Membuat folder baru
        MediaFolder::create([
            'name' => $request->name,
            'parent_id' => $parentId, // Menetapkan parent ID jika ada
        ]);

        return redirect()->route('media.index')->with('success', 'Folder created successfully!');
    }

    public function show($id)
    {
        $folder = MediaFolder::findOrFail($id);
        return view('media.folder.show', compact('folder'));
    }
}