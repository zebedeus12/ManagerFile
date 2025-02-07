<?php

// app/Models/File.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';
    protected $fillable = ['name', 'size', 'type', 'folder_id', 'created_by', 'keterangan'];

    // Relasi ke folder
    public function folder()
    {
        return $this->hasMany(File::class);
    }

    // Relasi ke user yang membuat file
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function index()
    {
        $folders = Folder::with('files')->get(); // Ambil folder beserta file-nya
        return view('file_manager.index', compact('folders'));
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
