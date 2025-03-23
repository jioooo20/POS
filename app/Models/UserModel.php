<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'level_id',
        'username',
        'nama',
        'password',
    ];

    protected $hidden = [
        'password', //sembunyikan pw saat select
    ];

    protected $casts = [
        'password' => 'hashed', //casting pw agar dihash otomatis
    ];


    public function level()
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }
}
