<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MediaFolder;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mediaItems = Media::all();
        $folders = MediaFolder::whereNull('parent_id')->with('subfolders')->get();
        return view('media.index', compact('mediaItems', 'folders'));
    }

    public function create(Request $request)
    {
        $folderId = $request->input('folder_id'); // Ambil folder_id dari request
        return view('media.create', compact('folderId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,mp3,wav,ogg,mp4,mkv,avi|max:10240',
            'folder_id' => 'nullable|exists:mysql_second.media_folders,id',
            'description' => 'nullable|string|max:255',
        ]);

        // Ambil nama file
        $fileName = $request->file('file')->getClientOriginalName();

        // Simpan file ke disk 'media'
        $filePath = $request->file('file')->store('uploads', 'public');
        $type = $request->file('file')->getClientMimeType();

        // Simpan informasi media ke database
        Media::create([
            'name' => $fileName,
            'path' => $filePath,
            'type' => $type,
            'folder_id' => $request->folder_id,
            'description' => $request->description,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('media.index')->with('success', 'Media berhasil ditambahkan ke folder!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Ambil media berdasarkan ID
        $media = Media::findOrFail($id);
        return view('media.edit', compact('media'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Media $media)
    {
        // dd($request);
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp3,mp4,mkv',
        ]);

        // Periksa apakah ada file baru yang diunggah
        if ($request->hasFile('file')) {
            try {
                // Hapus file lama jika ada
                if ($media->path && Storage::exists($media->path)) {
                    Storage::delete($media->path);
                }

                // Simpan file baru ke storage
                $filePath = $request->file('file')->store('uploads/media');
                $media->path = $filePath;
            } catch (\Exception $e) {
                return back()->withErrors('Error uploading file: ' . $e->getMessage());
            }
        }

        // Perbarui nama dan tipe media
        $media->name = $request->name;
        $media->type = $request->type;
        $media->save();

        // Redirect dengan pesan sukses
        return redirect()->route('media.index')->with('success', 'Media berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        // Hapus file dari storage jika ada
        if ($media->path && Storage::exists($media->path)) {
            Storage::delete($media->path);
        }

        // Hapus data dari database
        $media->delete();

        return redirect()->route('media.index')->with('success', 'Media berhasil dihapus.');
    }

    public function searchFolders(Request $request)
    {
        $search = $request->query('search', '');

        // Cari folder berdasarkan nama
        $folders = MediaFolder::where('name', 'like', "%{$search}%")->get();

        return view('media.index', compact('folders', 'search'));
    }

}