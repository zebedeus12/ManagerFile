<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $connection = 'mysql_second';
    use HasFactory;
    protected $fillable = ['name', 'path', 'type'];
}
