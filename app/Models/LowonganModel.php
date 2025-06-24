<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LowonganModel extends Model
{
    use HasFactory;

    protected $table = 't_lowongan';
    protected $primaryKey = 'lowongan_id';

    protected $fillable = [
        'user_id',
        'posisi_id',
        'lowongan_kode',
        'lowongan_nama',
        'lowongan_lokasi',
        'lowongan_deskripsi',
        'lowongan_kualifikasi',
        'lowongan_expired'
    ];

    protected $casts = [
        'lowongan_expired' => 'datetime'
    ];

    // Relationship to User (Perusahaan)
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    // Relationship to Posisi
    public function posisi()
    {
        return $this->belongsTo(PosisiModel::class, 'posisi_id', 'posisi_id');
    }
}
