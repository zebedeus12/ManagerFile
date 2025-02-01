<?php

namespace App\Http\Controllers;
use App\Models\Folder;
use App\Models\MediaFolder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil folder induk dari model Folder
        $folders = Folder::with('children', 'files')
            ->whereNull('parent_id')  // Hanya folder induk
            ->get();

        // Ambil folder induk dari model MediaFolder
        $mediaFolders = MediaFolder::with('subfolders')
            ->whereNull('parent_id')  // Hanya folder induk
            ->get();

        // Gabungkan kedua jenis folder tersebut
        $allFolders = $folders->merge($mediaFolders);

        // Kelompokkan berdasarkan tanggal
        $foldersGroupedByDate = $allFolders->groupBy(function ($folder) {
            return $folder->created_at->format('Y-m-d');  // Kelompokkan berdasarkan tanggal
        })->sortKeysDesc();

        // Kirim data folder yang telah dikelompokkan ke view
        return view('dashboard', compact('foldersGroupedByDate'));
    }
}