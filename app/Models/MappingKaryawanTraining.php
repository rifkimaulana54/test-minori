<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MappingKaryawanTraining extends Model
{
    use HasFactory;
    protected $table = 'mapping_training_pegawais';
    protected $primaryKey = 'id';
    protected $fillable = [
        'pegawai_id', 'training_id'
    ];

    public $timestamps = false;
    
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }
}
