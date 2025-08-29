<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFolder extends Model
{
    use HasFactory;
    protected $connection = 'mysql_second';
    protected $table = 'media_folders';
    protected $primaryKey = 'id'; // Pastikan primary key adalah `id`
    public $timestamps = true; // Pastikan Laravel menggunakan kolom `created_at` dan `updated_at`
    protected $fillable = ['name', 'parent_id', 'description', 'accessibility', 'owner_id', 'accessibility_subfolder'];

    // Relasi ke subfolder (self-referencing)
    public function subfolders()
    {
        return $this->hasMany(MediaFolder::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(MediaFolder::class, 'parent_id');
    }

    // Relasi ke media di dalam folder
    public function mediaItems()
    {
        return $this->hasMany(Media::class, 'folder_id');
    }
}
