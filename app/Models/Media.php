<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $connection = 'mysql_second';
    protected $fillable = ['name', 'path', 'type', 'file', 'folder_id']; // Pastikan ada folder_id

    public function folder()
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id');
    }
}
