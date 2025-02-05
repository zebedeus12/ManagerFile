<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $query = Folder::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('keterangan', 'like', '%' . $request->search . '%');
        }

        $folders = $query->whereNull('parent_id')->get(); // Ambil folder root saja

        // Ambil semua file
        $files = File::all(); // Ambil semua file

        return view('files.index', compact('folders', 'files')); // Kirim folders dan files ke tampilan
    }

    public function create($folder = null)
    {
        $folder = $folder ? Folder::findOrFail($folder) : null; // Temukan folder atau null jika root
        return view('files.create', compact('folder'));
    }

    public function store(Request $request, $folder = null)
    {
        $request->validate([
            'file' => 'required|file|max:2048|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $filePath = $uploadedFile->store('uploads', 'public');

            // Validasi folder jika ada
            $folderId = $folder ? Folder::findOrFail($folder)->id : null;

            // Simpan metadata file ke database
            $file = new File([
                'name' => $uploadedFile->getClientOriginalName(),
                'size' => $uploadedFile->getSize() / 1024, // dalam KB
                'type' => $uploadedFile->extension(),
                'path' => $filePath,
                'folder_id' => $folder, // Hubungkan file ke folder jika ada
                'created_by' => auth()->id(),
                'keterangan' => $request->input('keterangan'),
            ]);
            $file->save();
        }

        // Redirect ke folder tempat file diunggah
        if ($folder) {
            return redirect()->route('folder.show', $folder)->with('success', 'File berhasil diunggah.');
        }

        // Redirect ke halaman utama jika diunggah ke root
        return redirect()->route('file.index')->with('success', 'File berhasil diunggah.');
    }

    public function show($folderId)
    {
        $folder = Folder::with(['children', 'files'])->findOrFail($folderId); // Temukan folder berdasarkan ID
        $subFolders = $folder->children; // Subfolder di dalam folder ini
        $files = $folder->files; // File di dalam folder ini

        return view('folder.show', compact('folder', 'subFolders', 'files'));
    }

    // Fungsi Rename File
    public function rename(Request $request, $fileId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $file = File::findOrFail($fileId); // Temukan file berdasarkan ID
        $file->name = $request->input('name'); // Perbarui nama file
        $file->save();

        return redirect()->back()->with('success', 'File berhasil diubah namanya.');
    }

    // Fungsi Delete File
    public function destroy($fileId)
    {
        $file = File::findOrFail($fileId); // Temukan file berdasarkan ID

        if ($file->path && Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path); // Hapus file dari storage
        }

        $file->delete(); // Hapus metadata dari database

        return redirect()->back()->with('success', 'File berhasil dihapus.');
    }


    // Fungsi Share File
    public function share($fileId)
    {
        $file = File::findOrFail($fileId); // Temukan file berdasarkan ID
        $shareUrl = asset('storage/' . $file->path); // Dapatkan URL publik file

        return response()->json(['url' => $shareUrl]); // Kembalikan URL sebagai respons JSON
    }

    public function preview($id)
    {
        // Ambil path file dari database berdasarkan ID
        $file = File::findOrFail($id);
        $filePath = 'public/' . $file->path;

        // Periksa apakah file ada
        if (!Storage::exists($filePath)) {
            abort(404, 'File not found');
        }

        // Tentukan MIME type dan tampilkan file
        $fileContent = Storage::get($filePath);
        $mimeType = Storage::mimeType($filePath);

        return Response::make($fileContent, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $file->name . '"',
        ]);
    }
}
