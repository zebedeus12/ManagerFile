<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\File;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class FolderController extends Controller
{
    public function index(Request $request)
    {
        // Ambil folder yang tidak memiliki parent_id (folder induk)
        $folders = Folder::with('children')->whereNull('parent_id')->get();

        // Ambil semua file
        $files = File::all();

        $employees = Employee::whereIn('role', ['admin', 'super_admin'])->get();

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
        $folder->owner_id = $request->input('owner_id') ?? auth()->user()->id_user;

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
        $user = Auth()->user();

        if ($user->role === 'super_admin') {
            // Super admin bisa lihat semua (tidak ada filter)
        } elseif ($user->role === 'admin') {
            // Admin bisa lihat semua folder seperti super admin
            // Tidak perlu filter apapun
        } else {
            // User biasa hanya bisa lihat public
            $subFolderQuery->where('accessibility', 'public');
        }

        // Jika ada parameter pencarian
        if ($request->has('search')) {
            $search = $request->search;

            // Filter sub-folder berdasarkan pencarian
            $subFolderQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('keterangan', 'like', '%' . $search . '%');
            });

            // Filter file berdasarkan pencarian
            $fileQuery->where('name', 'like', '%' . $search . '%');

            // Terapkan filter akses yang sama seperti di atas
            if ($user->role === 'super_admin') {
                // Super admin bisa lihat semua (tidak ada filter)
            } elseif ($user->role === 'admin') {
                // Admin bisa lihat semua folder seperti super admin
                // Tidak perlu filter apapun
            } else {
                // User biasa hanya bisa lihat public
                $subFolderQuery->where('accessibility', 'public');
            }
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

        $hasChildren = $folder->children()->exists();
        $hasFiles = $folder->files()->exists();

        if ($hasChildren || $hasFiles) {
            return back()->with('error', 'Jika Anda ingin menghapus folder, kosongkan folder terlebih dahulu.');
        }

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

    public function bulkDelete(Request $request)
    {
        $ids = json_decode($request->input('ids'), true);

        if (!is_array($ids)) {
            return redirect()->back()->with('error', 'Tidak ada folder yang dipilih.');
        }

        $notDeletedFolders = [];

        foreach ($ids as $id) {
            $folder = Folder::find($id);

            if (!$folder) {
                continue;
            }

            $hasChildren = $folder->children()->exists();
            $hasFiles = $folder->files()->exists();

            if ($hasChildren || $hasFiles) {
                $notDeletedFolders[] = $folder->name;
            } else {
                $folder->delete(); // Hapus jika kosong
            }
        }

        if (count($notDeletedFolders) > 0) {
            return redirect()->back()->with('error', 'Folder berikut tidak dapat dihapus karena tidak kosong: ' . implode(', ', $notDeletedFolders));
        }

        return redirect()->back()->with('success', 'Folder kosong berhasil dihapus.');
    }


    public function download(Folder $folder)
    {
        $files = File::where('folder_id', $folder->id)->get();

        if ($files->count() == 0) {
            return back()->with('error', 'Tidak ada file didalam folder.');
        }

        $zipFileName = $folder->name . '.zip';

        $zipPath = storage_path("app/temp/{$zipFileName}");

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach ($files as $file) {

                $filePath = storage_path("app/public/uploads/{$file->original_name}");
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
        $folder = Folder::findOrFail($id);

        if (auth()->user()->id_user !== $folder->owner_id && !in_array(auth()->user()->role, ['super_admin', 'admin'])) {
            abort(403, 'Tidak diizinkan');
        }

        $folder->accessibility = $folder->accessibility === 'private' ? 'public' : 'private';
        $folder->save();

        return redirect()->back()->with('success', 'Akses folder berhasil diubah.');
    }

    public function setToAll($id)
    {
        $folder = Folder::findOrFail($id);

        if (auth()->user()->id_user !== $folder->owner_id && !in_array(auth()->user()->role, ['super_admin', 'admin'])) {
            abort(403, 'Tidak diizinkan');
        }

        // Toggle antara ALL dan ONLY ME
        $folder->accessibility_subfolder = $folder->accessibility_subfolder == 1 ? 0 : 1;
        $folder->save();

        $message = $folder->accessibility_subfolder == 1
            ? 'Akses folder berhasil diubah menjadi All.'
            : 'Akses folder berhasil diubah menjadi Only Me.';

        return redirect()->back()->with('success', $message);
    }
}
