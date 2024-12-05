<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFolder extends Model
{
    use HasFactory;
    protected $connection = 'mysql_second';
    protected $fillable = ['name', 'parent_id'];

    // Relasi ke subfolder (self-referencing)
    public function subfolders()
    {
        return $this->hasMany(MediaFolder::class, 'parent_id');
    }

    // Relasi ke media di dalam folder
    public function mediaItems()
    {
        return $this->hasMany(Media::class, 'folder_id');
    }
}
