<?php

// app/Models/Folder.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';
    protected $fillable = ['name', 'is_secret', 'created_by'];
}

// app/Models/File.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';
    protected $fillable = ['name', 'folder_id', 'created_by'];
}

