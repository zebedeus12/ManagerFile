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

        return redirect()->route('file.index')->with('success', 'Folder berhasil dibuat');
    }


    public function show(Folder $folder)
    {
        // Mengambil semua sub-folder dan file yang terkait
        $subFolders = $folder->children; // Relasi ke sub-folder
        $files = $folder->files; // Relasi ke file yang ada di folder ini

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

    public function copy(Request $request, $id)
    {

        // Ambil folder yang ingin disalin
        $folder = Folder::with('children', 'files')->findOrFail($id);

        // Jika ada folder tujuan yang dipilih
        $destinationFolderId = $request->input('destination_folder_id');

        // Jika tidak ada folder tujuan, gunakan folder induk
        $destinationFolder = $destinationFolderId ? Folder::findOrFail($destinationFolderId) : null;

        // Salin folder
        $newFolder = $folder->replicate();
        $newFolder->name = $folder->name . ' (Copy)';

        // Tentukan folder tujuan
        if ($destinationFolder) {
            $newFolder->parent_id = $destinationFolder->id;
        }

        $newFolder->save();

        // Salin sub-folder jika ada
        if ($folder->children->isNotEmpty()) {
            foreach ($folder->children as $child) {
                $this->copySubFolder($child, $newFolder->id);
            }
        }

        // Salin file di folder
        foreach ($folder->files as $file) {
            $newFile = $file->replicate();
            $newFile->folder_id = $newFolder->id;
            $newFile->save();
        }

        // Redirect ke halaman folder tujuan yang baru
        return redirect()->route('folder.show', ['folder' => $destinationFolderId])
            ->with('success', 'Folder berhasil disalin.');


    }

    protected function copySubFolder($subFolder, $newParentId)
    {
        $newSubFolder = $subFolder->replicate();  // Menyalin sub-folder
        $newSubFolder->parent_id = $newParentId;  // Mengatur parent folder baru
        $newSubFolder->save();

        // Salin file di sub-folder ini jika ada
        foreach ($subFolder->files as $file) {
            $newFile = $file->replicate();
            $newFile->folder_id = $newSubFolder->id;
            $newFile->save();
        }

        // Rekursif: Salin anak-anak folder jika ada
        if ($subFolder->children->isNotEmpty()) {
            foreach ($subFolder->children as $child) {
                $this->copySubFolder($child, $newSubFolder->id);
            }
        }
    }

}
