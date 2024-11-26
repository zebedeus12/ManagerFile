<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\File;
use Illuminate\Support\Facades\Log;

class FolderController extends Controller
{
    public function index()
    {
        // Ambil folder yang tidak memiliki parent_id (folder induk)
        $folders = Folder::with('children')->whereNull('parent_id')->get();

        // Ambil semua file
        $files = File::all();

        return view('files.index', compact('folders', 'files'));
    }

    public function showForm($parentId = null)
    {
        $parentFolder = null;
        if ($parentId) {
            $parentFolder = Folder::find($parentId);
        }

        return view('folder.form', compact('parentFolder'));
    }

    public function store(Request $request, $parentId = null)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'accessibility' => 'required|in:public,private',
        ]);

        $folder = new Folder();
        $folder->name = $request->input('folder_name');
        $folder->accessibility = $request->input('accessibility');

        if ($parentId) {
            $folder->parent_id = $parentId;
        }

        $folder->save();

        if ($request->ajax()) {
            // Jika permintaan adalah AJAX, kembalikan response JSON
            return response()->json([
                'success' => true,
                'folder' => $folder
            ]);
        }

        return redirect()->route('file.index')->with('success', 'Folder berhasil dibuat');
    }


    public function show(Folder $folder)
    {
        // Mengambil semua sub-folder dan file yang terkait
        $subFolders = $folder->children; // Relasi ke sub-folder
        $files = $folder->files; // Relasi ke file yang ada di folder ini

        return view('folder.show', compact('folder', 'subFolders', 'files'));
    }

    public function rename(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $folder = Folder::findOrFail($id);
        $folder->name = $request->input('name');
        $folder->save();

        return redirect()->route('file.index')->with('success', 'Folder berhasil diubah namanya.');
    }

    public function share($id)
    {
        $folder = Folder::findOrFail($id);
        $folder->shared = true; // Asumsi kolom `shared` ada di tabel
        $folder->save();

        return redirect()->route('file.index')->with('success', 'Folder berhasil dibagikan.');
    }

    public function destroy($id)
    {
        $folder = Folder::findOrFail($id);
        $folder->delete();

        return redirect()->route('file.index')->with('success', 'Folder berhasil dihapus.');
    }

    public function copy($id)
    {
        $folder = Folder::findOrFail($id);
        $newFolder = $folder->replicate();
        $newFolder->name = $folder->name . ' (Copy)';
        $newFolder->save();

        return redirect()->route('file.index')->with('success', 'Folder berhasil disalin.');
    }
}
