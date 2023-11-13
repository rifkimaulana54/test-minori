<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;
    protected $table = 'trainings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'jenis', 'tgl_sertifikat', 'keterangan'
    ];

    public function mappings()
    {
        return $this->hasMany(MappingKaryawanTraining::class);
    }
}
