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
        $user = Auth()->user();
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%');
            });
        }

        if ($user->role === 'super_admin') {
            // Tidak perlu filter, bisa lihat semua
        } elseif ($user->role === 'admin') {
            // Bisa lihat public + milik sendiri (private/public)
            $query->where(function ($q) use ($user) {
                $q->where('accessibility', 'public')
                  ->orWhere('owner_id', $user->id_user);
            });
        } else {
            // User biasa: hanya bisa lihat yang public
            $query->where('accessibility', 'public');
        }

        $folders = $query->whereNull('parent_id')->get();

        $employees = Employee::whereIn('role', ['admin', 'super_admin'])->get();
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
        // Debug tahap awal
        \Log::info("Memulai proses upload file", ['folder' => $folder]);

        $request->validate([
            'file.*' => 'required|file|max:50000|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $folderId = $request->input('folder_id') ?? $folder;
        if (!$folderId) {
            $folderId = null;
        }

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $uploadedFile) {
                if (!$uploadedFile->isValid()) {
                    return redirect()->back()->with('error', 'File tidak valid atau terjadi kesalahan saat mengunggah.');
                }

                // Buat nama file unik
                $uniqueName = time() . '-' . $uploadedFile->getClientOriginalName();
                $filePath = $uploadedFile->storeAs('uploads', $uniqueName, 'public');

                if (!$filePath) {
                    return redirect()->back()->with('error', 'Gagal menyimpan file.');
                }

                File::create([
                    'name' => $uploadedFile->getClientOriginalName(),
                    'original_name' => $uniqueName,
                    'size' => $uploadedFile->getSize() / 1024, // dalam KB
                    'type' => $uploadedFile->extension(),
                    'path' => $filePath,
                    'folder_id' => $folderId,
                    'created_by' => auth()->id(),
                    'keterangan' => $request->input('keterangan'),
                ]);
            }
        }

        if ($folderId && Folder::find($folderId)) {
            return redirect()->route('folder.show', $folderId)->with('success', 'File berhasil diunggah.');
        }

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
        $file = File::find($fileId); // Find the file by ID

        // Check if the file exists in storage and delete it
        if ($file->path && Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path); // Delete the file from storage
        }

        $file->delete(); // Delete the file metadata from the database

        return redirect()->route('file.index')->with('success', 'File successfully deleted.'); // Redirect back with success message
    }

    // Fungsi Share File
    public function share($fileId)
    {
        $file = File::findOrFail($fileId); // Temukan file berdasarkan ID
        $shareUrl = asset('storage/' . $file->path); // Dapatkan URL publik file

        return response()->json(['url' => $shareUrl]); // Kembalikan URL sebagai respons JSON
    }

    public function download(File $file)
    {
        $filePath = 'uploads/' . $file->original_name;

        // Mengecek apakah file ada di storage public
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }

        // Tentukan MIME type file
        $mimeType = Storage::disk('public')->mimeType($filePath);

        // Ambil isi file
        $fileContent = Storage::disk('public')->get($filePath);

        // Mengatur header untuk mengunduh file
        return response()->stream(
            function () use ($fileContent) {
                echo $fileContent;
            },
            200,
            [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'attachment; filename="' . $file->original_name . '"',  // Mengunduh file, bukan menampilkannya
                'Content-Length' => strlen($fileContent),  // Menentukan ukuran file
            ]
        );
    }

    public function bulkDelete(Request $request)
    {
        $ids = json_decode($request->input('ids'), true);

        if (is_array($ids)) {
            File::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'File berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Tidak ada file yang dipilih.');
    }
}
