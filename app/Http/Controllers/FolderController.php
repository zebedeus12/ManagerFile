<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function showForm()
    {
        return view('folder.form');
    }

    // app/Http/Controllers/FolderController.php
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'accessibility' => 'required|in:public,private',
        ]);

        // Simpan data folder ke database
        \DB::connection('mysql_second')->table('folders')->insert([
            'name' => $request->input('folder_name'),
            'accessibility' => $request->input('accessibility'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('folder.form')->with('success', 'Folder berhasil dibuat');
    }

}
