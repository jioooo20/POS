<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosisiModel extends Model
{
    use HasFactory;

    protected $table = 'm_posisi';
    protected $primaryKey = 'posisi_id';

    protected $fillable = [
        'posisi_kode',
        'posisi_nama'
    ];

    // Relationship to Lowongan (one to many)
    public function lowongan()
    {
        return $this->hasMany(LowonganModel::class, 'posisi_id', 'posisi_id');
    }
}
