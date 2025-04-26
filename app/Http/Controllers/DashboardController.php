<?php

namespace App\Http\Controllers;
use App\Models\Folder;
use App\Models\MediaFolder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil folder induk dari model Folder
        $folderQuery = Folder::with('children', 'files')
            ->whereNull('parent_id');

        // Ambil folder induk dari model MediaFolder
        $mediaFolderQuery = MediaFolder::with('subfolders')
            ->whereNull('parent_id');

        // ===== Role based access control =====
        if ($user->role === 'super_admin') {
            // Tidak ada filter, ambil semua
        } elseif ($user->role === 'admin') {
            // Admin: hanya public atau yang dimiliki sendiri
            $folderQuery->where(function ($q) use ($user) {
                $q->where('accessibility', 'public')
                ->orWhere('owner_id', $user->id_user);
            });

            $mediaFolderQuery->where(function ($q) use ($user) {
                $q->where('accessibility', 'public')
                ->orWhere('owner_id', $user->id_user);
            });

        } else {
            // User biasa: hanya folder public
            $folderQuery->where('accessibility', 'public');
            $mediaFolderQuery->where('accessibility', 'public');
        }

        // Eksekusi query
        $folders = $folderQuery->get();
        $mediaFolders = $mediaFolderQuery->get();

        // Gabungkan kedua jenis folder
        $allFolders = $folders->merge($mediaFolders);

        // Kelompokkan berdasarkan tanggal
        $foldersGroupedByDate = $allFolders->groupBy(function ($folder) {
            return $folder->created_at->format('Y-m-d');
        })->sortKeysDesc();
        
        return view('dashboard', compact('foldersGroupedByDate'));
    }

}