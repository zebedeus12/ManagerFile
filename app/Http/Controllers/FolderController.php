<?php

// app/Http/Controllers/FolderController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;

class FolderController extends Controller
{
    public function showForm()
    {
        return view('folder.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'accessibility' => 'required|in:public,private',
        ]);

        Folder::create([
            'name' => $request->input('folder_name'),
            'accessibility' => $request->input('accessibility'),
        ]);

        return redirect()->route('folder.form')->with('success', 'Folder berhasil dibuat');
    }
}
