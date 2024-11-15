<?php

// app/Models/File.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';
    protected $fillable = ['name', 'size', 'type', 'folder_id', 'created_by'];

    // Relasi ke folder
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    // Relasi ke user yang membuat file
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
