<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    // Specify the table to use
    protected $table = 'tb_arsipuser_copy';
    protected $primaryKey = 'id_user'; // Sesuaikan dengan nama primary key di tabel Anda
    public $timestamps = false;

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'nama_user',
        'login',
        'password',
        'role',
        'id_struktural_tim',
    ];


    public function getAuthPassword()
    {
        return $this->password;
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

}
