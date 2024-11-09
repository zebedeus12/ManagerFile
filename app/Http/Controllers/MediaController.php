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
        $mediaItems = Media::all();
        return view('media.index', compact('mediaItems'));
    }

    /**
     * Search media by name.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $mediaItems = Media::where('name', 'LIKE', "%$query%")->get();
        
        return view('media.index', compact('mediaItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('media.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'file' => 'required|file|mimes:jpg,jpeg,png,mp3,mp4,pdf|max:20480',
            'type' => 'required|string',
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
        $media = Media::findOrFail($id);
        return view('media.edit', compact('media'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Media $media)
    {
        $media = Media::findOrFail($media->id);
        $request->validate([
            'file' => 'mimes:jpeg,png,jpg,gif,mp3,mp4,mkv|max:20480',
            'name' => 'required|string',
            'type' => 'required|string',
        ]);
    
        if ($request->hasFile('file')) {
            if ($media->path) {
                Storage::delete($media->path);
            }
    
            $filePath = $request->file('file')->store('uploads/media');
            $media->path = $filePath;
        }
    
        $media->name = $request->name;
        $media->type = $request->type;
        $media->save();
    
        return redirect()->route('media.index')->with('success', 'Media berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        $media = Media::findOrFail($media->id);
        
        if ($media->path) {
            Storage::delete($media->path);
        }

        $media->delete();
    
        return response()->json(['success' => 'Media berhasil dihapus']);
    }
}
