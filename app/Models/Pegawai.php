<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = 'pegawais';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nip', 'nama_karyawan', 'jabatan'
    ];

    public function mappings()
    {
        return $this->hasMany(MappingKaryawanTraining::class);
    }
}
