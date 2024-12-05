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
        // Ambil semua media
        $mediaItems = Media::all();
        // Ambil semua folder
        $folders = MediaFolder::all();

        return view('media.index', compact('mediaItems', 'folders'));
    }

    /**
     * Search media by name.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        // Cari media berdasarkan nama
        $mediaItems = Media::where('name', 'LIKE', "%$query%")->get();

        return view('media.index', compact('mediaItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($parentId = null)
    {
        // Logika untuk menampilkan form untuk membuat folder baru.
        return view('media.create', compact('parentId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $parentId = null)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Logika untuk menyimpan folder baru
        $folder = new MediaFolder();
        $folder->name = $request->name;

        // Jika ada parentId, set parent folder
        if ($parentId) {
            $folder->parent_id = $parentId;
        }

        $folder->save();

        return redirect()->route('media.index')->with('success', 'Folder created successfully!');
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


}
