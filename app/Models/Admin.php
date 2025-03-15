<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // <<=== C'est ça qui manque sûrement
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    
    protected $table = 'admins';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
