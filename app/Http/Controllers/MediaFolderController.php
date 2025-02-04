<?php
namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\MediaFolder;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

class MediaFolderController extends Controller
{
    public function index()
    {
        // Ambil semua folder dan media
        $folders = MediaFolder::with('subfolders')->get();
        $mediaItems = Media::all();
        // Tambahkan ini untuk mengambil data admin

        // Kirim variabel $employees ke view
        return view('media.index', compact('folders', 'mediaItems', 'employees'));
    }


    public function create($parentId = null)
    {
        $employees = Employee::where('role', 'admin')->get();
        // Menampilkan form untuk membuat folder, bisa subfolder atau folder utama
        return view('media.createFolder', compact('parentId', 'employees'));
    }

    public function store(Request $request, $parentId = null)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'accessibility' => 'required|in:public,private',
            'description' => 'nullable|string|max:255',
        ]);

        // Jika $parentId null, maka folder ini adalah folder utama
        MediaFolder::create([
            'name' => $request->name,
            'accessibility' => $request->accessibility,
            'description' => $request->description,
            'parent_id' => $parentId, // Jika null, maka parent_id juga null
        ]);

        return redirect()->route('media.index', ['id' => $parentId ?? MediaFolder::latest()->first()->id])
            ->with('success', 'Folder created successfully!');
    }

    // Menambahkan fungsi untuk membuat media baru di dalam folder
    public function createMedia($folderId)
    {
        $folder = MediaFolder::findOrFail($folderId);
        return view('media.createMedia', compact('folder'));
    }

    public function storeMedia(Request $request, $folderId)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,docx|max:10240',
            'name' => 'required|string|max:255',
        ]);

        // Proses upload file
        $path = $request->file('file')->store('media', 'public');

        // Simpan media ke folder yang dipilih
        Media::create([
            'name' => $request->name,
            'path' => $path,
            'folder_id' => $folderId,
        ]);

        return redirect()->route('media.folder.show', $folderId)
            ->with('success', 'Media uploaded successfully!');
    }

    public function show(Request $request, $id)
    {
        // Ambil folder yang diminta dengan subfolder dan media
        $folder = MediaFolder::findOrFail($id);

        // Query subfolder dengan filter pencarian
        $subfolderQuery = $folder->subfolders();

        if ($request->has('search')) {
            $search = $request->input('search');
            $subfolderQuery->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        }

        $subfolders = $subfolderQuery->get();

        // Query media dengan filter pencarian
        $mediaQuery = $folder->mediaItems();

        if ($request->has('search')) {
            $search = $request->input('search');
            $mediaQuery->where('name', 'like', '%' . $search . '%')
                ->orWhere('type', 'like', '%' . $search . '%');
        }

        $mediaItems = $mediaQuery->get();

        // Ambil data admin
        $employees = Employee::where('role', 'admin')->get();

        return view('media.folder.show', compact('folder', 'subfolders', 'mediaItems', 'employees'));
    }

    public function rename(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Cari folder berdasarkan ID
        $folder = MediaFolder::findOrFail($id);

        // Update nama folder
        $folder->name = $request->name;
        $folder->save();

        // Redirect ke halaman media manager dengan pesan sukses
        return redirect()->route('media.index')->with('success', 'Folder renamed successfully!');
    }

    public function share($id)
    {
        $folder = MediaFolder::findOrFail($id);

        // Anda bisa menambahkan logika di sini untuk memvalidasi atau membuat link share

        // Redirect ke halaman folder atau tampilkan halaman share
        return response()->json([
            'share_url' => route('media.folder.share', ['id' => $folder->id]),
        ]);
    }

    public function destroy($id)
    {
        $folder = MediaFolder::findOrFail($id);

        // Logika tambahan (opsional): Hapus semua media dan subfolder jika ada
        $folder->mediaItems()->delete(); // Jika folder memiliki media
        $folder->subfolders()->delete(); // Jika folder memiliki subfolder

        // Hapus folder
        $folder->delete();

        return redirect()->route('media.index')->with('success', 'Folder deleted successfully!');
    }

    public function destroyMultiple(Request $request)
    {
        $folderIds = $request->input('folders'); // Ambil daftar folder_id dari request

        if (!$folderIds || count($folderIds) === 0) {
            return redirect()->route('media.index')->with('error', 'Tidak ada folder yang dipilih.');
        }

        // Loop untuk memeriksa setiap folder sebelum dihapus
        foreach ($folderIds as $id) {
            $folder = MediaFolder::with(['subfolders', 'mediaItems'])->find($id);

            if (!$folder)
                continue; // Jika folder tidak ditemukan, lanjut ke berikutnya

            // Cek jika folder memiliki subfolder atau media
            if ($folder->subfolders->isNotEmpty() || $folder->mediaItems->isNotEmpty()) {
                return redirect()->route('media.index')->with('error', 'Pastikan folder kosong sebelum menghapus.');
            }

            // Hapus folder jika kosong
            $folder->delete();
        }

        return redirect()->route('media.index')->with('success', 'Folder yang dipilih berhasil dihapus.');
    }

    public function checkFolder($id)
    {
        $folder = MediaFolder::with(['subfolders', 'mediaItems'])->findOrFail($id);

        // Cek apakah folder memiliki subfolder atau media
        $hasSubfolders = $folder->subfolders->isNotEmpty();
        $hasMediaItems = $folder->mediaItems->isNotEmpty();

        if ($hasSubfolders || $hasMediaItems) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jika Anda ingin menghapus folder, kosongkan folder terlebih dahulu.',
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Folder kosong dan siap untuk dihapus.',
        ], 200);
    }


}