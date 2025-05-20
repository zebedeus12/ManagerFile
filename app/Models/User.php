<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'tb_arsipuser';
    protected $primaryKey = 'id_user';
    protected $fillable = ['login', 'password'];
    public $timestamps = false;

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

}
