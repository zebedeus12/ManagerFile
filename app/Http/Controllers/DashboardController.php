<?php

namespace App\Http\Controllers;
use App\Models\Folder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil folder beserta subfolder dan file yang terhubung
        $folders = Folder::with('children', 'files')
            ->whereNull('parent_id')  // Hanya folder induk
            ->get();

        // Kelompokkan berdasarkan tanggal (misalnya menggunakan created_at)
        $foldersGroupedByDate = $folders->groupBy(function ($folder) {
            return $folder->created_at->format('Y-m-d');  // Kelompokkan berdasarkan tanggal
        });

        return view('dashboard', compact('foldersGroupedByDate'));
    }

}
