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

    public function stok()
    {
        return $this->hasMany(StokModel::class, 'user_id', 'user_id');
    }
    public function penjualan()
    {
        return $this->hasMany(PenjualanModel::class, 'user_id', 'user_id');
    }

    //ambil nama role
    public function getRoleName(): string{
        return $this->level->level_nama;
    }

    //cek apakah user memiliki role tertentu
    public function hasRole(string $role): bool{
        return $this->level->level_kode === $role;
    }

    public function getRole(){
        return $this->level->level_kode;
    }

}
