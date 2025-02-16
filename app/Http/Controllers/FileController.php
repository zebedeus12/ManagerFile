<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Models\Employee;
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

        $employees = Employee::where('role', 'admin')->get();
        // Ambil semua file
        $files = File::all(); // Ambil semua file

        return view('files.index', compact('folders', 'files', 'employees')); // Kirim folders dan files ke tampilan
    }

    public function create($folder = null)
    {
        $folder = $folder ? Folder::findOrFail($folder) : null; // Temukan folder atau null jika root
        return view('files.create', compact('folder'));
    }

    public function store(Request $request, $folder = null)
    {
        $request->validate([
            'file.*' => 'required|file|max:2048|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Ambil folder_id dari request atau gunakan nilai dari parameter
        $folderId = $request->input('folder_id') ?? $folder;

        if (!$folderId) {
            $folderId = null; // Jika tidak ada folder, masukkan ke root
        }

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $uploadedFile) {
                $filePath = $uploadedFile->store('uploads', 'public');

                // Simpan metadata file ke database
                File::create([
                    'name' => $uploadedFile->getClientOriginalName(),
                    'size' => $uploadedFile->getSize() / 1024, // dalam KB
                    'type' => $uploadedFile->extension(),
                    'path' => $filePath,
                    'folder_id' => $folderId, // Simpan folder_id agar masuk ke dalam folder
                    'created_by' => auth()->id(),
                    'keterangan' => $request->input('keterangan'),
                ]);
            }
        }

        // Redirect ke halaman folder atau index jika tidak ada folder
        return $folderId
            ? redirect()->route('folder.show', $folderId)->with('success', 'File berhasil diunggah.')
            : redirect()->route('file.index')->with('success', 'File berhasil diunggah.');
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
        $file = File::find($fileId); // Find the file by ID
        if (!$file) {
            return redirect()->back()->with('error', 'File not found.'); // Redirect back with error message
        }

        // Check if the file exists in storage and delete it
        if ($file->path && Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path); // Delete the file from storage
        }

        $file->delete(); // Delete the file metadata from the database

        return redirect()->back()->with('success', 'File berhasil dihapus.'); // Redirect back with success message
    }

    public function deleteMultiple(Request $request)
    {
        // Ambil ID file yang dikirimkan
        $fileIds = $request->input('files');

        // Pastikan ada file yang dipilih
        if ($fileIds) {
            foreach ($fileIds as $fileId) {
                $file = File::find($fileId);  // Temukan file berdasarkan ID
                if ($file) {
                    // Hapus file
                    $file->delete();
                }
            }

            // Redirect dengan pesan sukses
            return redirect()->route('file.index')->with('success', 'Files deleted successfully.');
        }

        // Jika tidak ada file yang dipilih
        return redirect()->back()->with('error', 'No files selected.');
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