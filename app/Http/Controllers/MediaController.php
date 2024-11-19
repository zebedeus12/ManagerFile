<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return view('media.index', compact('mediaItems'));
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
    public function create()
    {
        // Tampilkan form untuk menambahkan media baru
        return view('media.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpg,jpeg,png,mp3,mp4,pdf|max:20480',
            'type' => 'required|string|max:255',
        ]);
    
        $filePath = $request->file('file')->store('uploads/media');
    
        Media::create([
            'name' => $request->name,
            'path' => $filePath,
            'type' => $request->type,
        ]);
    
        return redirect()->route('media.index')->with('success', 'Media berhasil ditambahkan.');
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
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp3,mp4,mkv|max:20480',
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