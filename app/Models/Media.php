<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $connection = 'mysql_second';
    protected $table = 'media';
    protected $primaryKey = 'id'; // Pastikan primary key adalah `id`
    public $timestamps = true; // Kolom `created_at` dan `updated_at`

    protected $fillable = ['name', 'path', 'type', 'folder_id']; // Pastikan ada folder_id

    public function folder()
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id');
    }
}
