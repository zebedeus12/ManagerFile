<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\File;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;

class FolderController extends Controller
{
    public function index(Request $request)
    {
        // Ambil folder yang tidak memiliki parent_id (folder induk)
        $folders = Folder::with('children')->whereNull('parent_id')->get();

        // Ambil semua file
        $files = File::all();

        $employees = Employee::where('role', 'admin')->get();

        return view('files.index', compact('folders', 'files', 'employees'));
    }

    public function store(Request $request, $parentId = null)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'accessibility' => 'required|in:public,private',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $folder = new Folder();
        $folder->name = $request->input('folder_name');
        $folder->accessibility = $request->input('accessibility');
        $folder->keterangan = $request->input('keterangan');

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

        // Redirect back to the parent folder's show page
        if ($parentId) {
            return redirect()->route('folder.show', ['folder' => $parentId])->with('success', 'Subfolder berhasil dibuat');
        }

        // If no parent, redirect to the main folder page (index or a top-level folder)
        return redirect()->route('folder.index')->with('success', 'Folder berhasil dibuat');
    }

    public function show(Request $request, Folder $folder)
    {
        // Ambil semua sub-folder dan file yang terkait
        $subFolderQuery = $folder->children(); // Relasi ke sub-folder
        $fileQuery = $folder->files(); // Relasi ke file dalam folder ini

        // Jika ada parameter pencarian
        if ($request->has('search')) {
            $search = $request->search;

            // Filter sub-folder berdasarkan pencarian
            $subFolderQuery->where('name', 'like', '%' . $search . '%')
                ->orWhere('keterangan', 'like', '%' . $search . '%');

            // Filter file berdasarkan pencarian
            $fileQuery->where('name', 'like', '%' . $search . '%');
        }

        $subFolders = $subFolderQuery->get();
        $files = $fileQuery->get();

        // Semua folder utama untuk navigasi, tanpa filter pencarian
        $allFolders = Folder::whereNull('parent_id')->get();

        return view('folder.show', compact('folder', 'subFolders', 'files', 'allFolders'));
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

    public function checkFolder($id)
    {
        $folder = Folder::findOrFail($id);

        // Pastikan relasi children dan files sudah didefinisikan di model Folder
        $hasChildren = $folder->children()->exists(); // Cek apakah ada sub-folder
        $hasFiles = $folder->files()->exists();       // Cek apakah ada file

        if ($hasChildren || $hasFiles) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jika Anda ingin menghapus folder, kosongkan folder terlebih dahulu.',
            ], 400); // HTTP 400 untuk error
        }

        // Jika folder kosong
        return response()->json([
            'status' => 'success',
            'message' => 'Folder kosong dan siap untuk dihapus.',
        ], 200); // HTTP 200 untuk sukses
    }
}
