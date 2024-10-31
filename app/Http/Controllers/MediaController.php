<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
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
            'file' => 'required|mimes:jpeg,png,jpg,gif,mp3,mp4,mkv|max:20480',
            'name' => 'required|string',
            'type' => 'required|string',
        ]);

        $filePath = $request->file('file')->store('uploads/media');

        Media::create([
            'name' => $request->name,
            'path' => $filePath,
            'type' => $request->type,
        ]);

        return redirect()->route('media.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Media $media)
    {
        return view('media.edit', compact('media'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Media $media)
    {
        $request->validate([
            'file' => 'mimes:jpeg,png,jpg,gif,mp3,mp4,mkv|max:20480',
            'name' => 'required|string',
            'type' => 'required|string',
        ]);

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('uploads/media');
            $media->path = $filePath;
        }

        $media->name = $request->name;
        $media->type = $request->type;
        $media->save();

        return redirect()->route('media.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        $media->delete();
        return redirect()->route('media.index');
    }
}
