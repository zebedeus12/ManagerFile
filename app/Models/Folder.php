<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';
    protected $fillable = ['name', 'accessibility', 'parent_id'];

    // Relasi ke folder induk (parent)
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    // Relasi ke sub-folder (children)
    public function subFolders()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    // Relasi ke file yang ada di dalam folder
    public function files()
    {
        return $this->hasMany(File::class);
    }
}
