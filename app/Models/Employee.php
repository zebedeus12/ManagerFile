<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // Specify the table to use
    protected $table = 'users';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'name',
        'email',
        'role', // Assuming role is needed
    ];
}
