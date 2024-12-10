<?php
namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\MediaFolder;
use Illuminate\Http\Request;

class MediaFolderController extends Controller
{
    public function index()
    {
        // Ambil semua folder dan media
        $folders = MediaFolder::with('subfolders')->get();
        $mediaItems = Media::all();

        return view('media.index', compact('mediaItems', 'folders'));
    }

    public function create($parentId = null)
    {
        // Menampilkan form untuk membuat folder, bisa subfolder atau folder utama
        return view('media.createFolder', compact('parentId'));
    }

    public function store(Request $request, $parentId = null)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Jika $parentId null, maka folder ini adalah folder utama
        MediaFolder::create([
            'name' => $request->name,
            'parent_id' => $parentId, // Jika null, maka parent_id juga null
        ]);

        return redirect()->route('media.folder.show', ['id' => $parentId ?? MediaFolder::latest()->first()->id])
            ->with('success', 'Folder created successfully!');
    }

    // Menambahkan fungsi untuk membuat media baru di dalam folder
    public function createMedia($folderId)
    {
        $folder = MediaFolder::findOrFail($folderId);
        return view('media.createMedia', compact('folder'));
    }

    public function storeMedia(Request $request, $folderId)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,docx|max:10240',
            'name' => 'required|string|max:255',
        ]);

        // Proses upload file
        $path = $request->file('file')->store('media', 'public');

        // Simpan media ke folder yang dipilih
        Media::create([
            'name' => $request->name,
            'path' => $path,
            'folder_id' => $folderId,
        ]);

        return redirect()->route('media.folder.show', $folderId)
            ->with('success', 'Media uploaded successfully!');
    }

    public function show($id)
    {
        $folder = MediaFolder::with(['subfolders', 'mediaItems'])->findOrFail($id);
        return view('media.folder.show', compact('folder'));
    }

}