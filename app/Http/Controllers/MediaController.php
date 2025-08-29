<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MediaFolder;
use App\Models\Media;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $folderId = null)
    {
        $user = auth()->user();
        $employees = Employee::whereIn('role', ['admin', 'super_admin'])->get();
        $folderId = $folderId ?? $request->query('folder_id', null);

        // Query media sesuai akses user
        $mediaQuery = Media::query();

        if ($folderId) {
            $mediaQuery->where('folder_id', $folderId);
        }

        $mediaItems = $mediaQuery->get();

        // Folder query
        $foldersQuery = MediaFolder::query()->whereNull('parent_id')->with('subfolders');

        if ($request->has('search')) {
            $search = $request->input('search');
            $foldersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Akses folder berdasarkan role
        if ($user->role === 'super_admin') {
            // Semua folder
        } elseif ($user->role === 'admin') {
            $foldersQuery->where(function ($q) use ($user) {
                $q->where('accessibility', 'public')
                    ->orWhere('owner_id', $user->id_user);
            });
        } else {
            $foldersQuery->where('accessibility', 'public');
        }

        $folders = $foldersQuery->get();

        return view('media.index', compact('mediaItems', 'folders', 'employees'));
    }



    public function create(Request $request)
    {
        $folderId = $request->input('folder_id'); // Ambil folder_id dari request
        return view('media.create', compact('folderId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,mp3,wav,ogg,mp4,mkv,avi|max:500000',
            'folder_id' => 'nullable|exists:mysql_second.media_folders,id',
            'description' => 'nullable|string|max:255',
        ]);

        $fileNameOriginalName = $request->file('file')->getClientOriginalName();
        $fileName = time() . '-' . $request->file('file')->getClientOriginalName();

        // Simpan file ke disk 'media'
        $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
        $type = $request->file('file')->getClientMimeType();

        // Simpan informasi media ke database
        Media::create([
            'name' => $fileNameOriginalName,
            'path' => $fileName,
            'type' => $type,
            'folder_id' => $request->folder_id,
            'description' => $request->description,
            'original_name' => $fileName
        ]);

        // Redirect dengan pesan sukses
        return back()->with('success', 'Media berhasil ditambahkan ke folder!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Ambil media berdasarkan ID
        $media = Media::findOrFail($id);
        return view('media.edit', compact('media'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Media $media)
    {
        $folder = $media->folder; // Dapatkan folder induk dari media
        $user = auth()->user();

        // Otorisasi: Izinkan jika user adalah super_admin, pemilik folder, atau folder diatur ke 'All'
        if ($user->role !== 'super_admin' && $user->id_user != $folder->owner_id && $folder->accessibility_subfolder != 1) {
            return redirect()->route('media.index')->with('error', 'Anda tidak memiliki izin untuk mengedit media ini.');
        }

        // dd($request);
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp3,mp4,mkv,avi',
        ]);

        // Periksa apakah ada file baru yang diunggah
        if ($request->hasFile('file')) {
            try {
                // Hapus file lama jika ada
                if ($media->path && Storage::exists($media->path)) {
                    Storage::delete($media->path);
                }

                // Simpan file baru ke storage
                $filePath = $request->file('file')->store('uploads/media');
                $media->path = $filePath;
            } catch (\Exception $e) {
                return back()->withErrors('Error uploading file: ' . $e->getMessage());
            }
        }

        // Perbarui nama dan tipe media
        $media->name = $request->name;
        $media->type = $request->type;
        $media->save();

        // Redirect dengan pesan sukses
        return redirect()->route('media.index')->with('success', 'Media berhasil diupdate.');
    }

    public function destroy(Media $media)
    {
        $folder = $media->folder; // Dapatkan folder induk dari media
        $user = auth()->user();

        // Otorisasi: Izinkan jika user adalah super_admin, pemilik folder, atau folder diatur ke 'All'
        if ($user->role !== 'super_admin' && $user->id_user != $folder->owner_id && $folder->accessibility_subfolder != 1) {
            return redirect()->route('media.index')->with('error', 'Anda tidak memiliki izin untuk menghapus media ini.');
        }

        // Hapus file dari storage jika ada
        if ($media->path && Storage::exists($media->path)) {
            Storage::delete($media->path);
        }

        // Hapus data dari database
        $media->delete();

        return redirect()->route('media.index')->with('success', 'Media berhasil dihapus.');
    }

    public function show($id)
    {
        $media = Media::findOrFail($id);
        // FIX: Removed 'uploads' from the path construction since $media->path already contains it.
        $path = storage_path('app/public/uploads/' . $media->path); // Corrected line

        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        return response()->file($path);
    }

    public function download(Media $media)
    {
        $filePath = $media->path;

        // Mengecek apakah file ada di storage public
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }

        // Tentukan MIME type file
        $mimeType = Storage::disk('public')->mimeType($filePath);
        // dd($mimeType);
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
                'Content-Disposition' => 'attachment; filename="' . $media->original_name . '"',  // Mengunduh file, bukan menampilkannya
                'Content-Length' => strlen($fileContent),  // Menentukan ukuran file
            ]
        );
    }

    public function bulkDelete(Request $request)
    {
        $ids = json_decode($request->input('ids'), true);

        if (is_array($ids)) {
            MEdia::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Media berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Tidak ada media yang dipilih.');
    }
}

