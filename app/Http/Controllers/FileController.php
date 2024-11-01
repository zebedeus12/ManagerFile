<?php

namespace App\Http\Controllers;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index()
    {
        $folders = Folder::all();
        $files = File::all();

        return view('files.index', compact('folders', 'files'));
    }
    public function create()
    {
        return view('files.create');
    }

    public function store(Request $request)
    {
        Log::info('Memulai proses upload file.');
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);

        if ($request->hasFile('file')) {
            Log::info('File terdeteksi.');
            $filePath = $request->file('file')->store('uploads', 'public');
            Log::info('File berhasil di-upload ke: ' . $filePath);
        }

        return redirect()->route('files.create')->with('success', 'File berhasil diunggah.');
    }
}
