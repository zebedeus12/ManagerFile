<?php
namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\MediaFolder;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

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
        $employees = Employee::whereIn('role', ['admin', 'super_admin'])->get();
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
            'name'          => $request->name,
            'accessibility' => $request->accessibility,
            'description'   => $request->description,
            'parent_id'     => $parentId,
            'owner_id'      => $request->owner_id ?? auth()->user()->id_user
        ]);

        return back()->with('success', 'Folder created successfully!');
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
        $folder = MediaFolder::findOrFail($id);

        // Ambil query pencarian
        $search = $request->input('search');

        // Filter subfolder berdasarkan pencarian
        $subfolderQuery = $folder->subfolders();
        if ($search) {
            $subfolderQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        $subfolders = $subfolderQuery->get();

        // Filter media berdasarkan pencarian
        $mediaQuery = $folder->mediaItems();
        if ($search) {
            $mediaQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%');
            });
        }
        $mediaItems = $mediaQuery->get();

        // Ambil semua admin
        $employees = Employee::where('role', 'admin')->get();

        // ===== Breadcrumb building =====
        $breadcrumbs = [];
        $current = $folder;
        while ($current->parent) {
            $breadcrumbs[] = $current->parent;
            $current = $current->parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs); // urut dari root ke anak

        return view('media.folder.show', compact('folder', 'subfolders', 'mediaItems', 'employees', 'breadcrumbs'));
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

    public function download(MediaFolder $mediaFolder)
    {
        $files = Media::where('folder_id', $mediaFolder->id)->get();
   
        if ($files->count() == 0) {
            return back()->with('error', 'Tidak ada file didalam folder.');
        }

        $zipFileName = $mediaFolder->name . '.zip';

        $zipPath = storage_path("app/temp/{$zipFileName}");
        
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach ($files as $file) {
          
                $filePath = storage_path("app/public/{$file->path}");
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($filePath));
                }
            }

            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Gagal membuat ZIP.');
    }

    public function toggleAccessibility($id)
    {
        $mediaFolder = MediaFolder::findOrFail($id);

        if (auth()->user()->id_user !== $mediaFolder->owner_id && auth()->user()->role !== 'super_admin') {
            abort(403, 'Tidak diizinkan');
        }

        $mediaFolder->accessibility = $mediaFolder->accessibility === 'private' ? 'public' : 'private';
        $mediaFolder->save();

        return redirect()->back()->with('success', 'Akses folder berhasil diubah.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = json_decode($request->input('ids'), true);

        if (!is_array($ids)) {
            return redirect()->back()->with('error', 'Tidak ada media folder yang dipilih.');
        }

        $notDeletedFolders = [];

        foreach ($ids as $id) {
            $folder = MediaFolder::with(['subfolders', 'mediaItems'])->find($id);

            if (!$folder) {
                continue; // Skip jika folder tidak ditemukan
            }

            $hasSubfolders = $folder->subfolders->isNotEmpty();
            $hasMediaItems = $folder->mediaItems->isNotEmpty();

            if ($hasSubfolders || $hasMediaItems) {
                $notDeletedFolders[] = $folder->name;
            } else {
                $folder->delete();
            }
        }

        if (count($notDeletedFolders) > 0) {
            return redirect()->back()->with('error', 'Folder berikut tidak dapat dihapus karena tidak kosong: ' . implode(', ', $notDeletedFolders));
        }

        return redirect()->back()->with('success', 'Folder kosong berhasil dihapus.');
    }

}