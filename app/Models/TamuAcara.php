<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TamuAcara extends Model
{
    use HasFactory;

    protected $table = 'tamu_acaras'; // Sesuaikan nama tabel dengan database
    protected $fillable = [
        'id_tamu',
        'kode_acara',
        'status',
    ];

    // Relasi ke model Tamu
    public function tamu()
    {
        return $this->belongsTo(Tamu::class, 'id_tamu', 'id_tamu');
    }

    // Relasi ke model Event
    public function event()
    {
        return $this->belongsTo(Event::class, 'kode_acara', 'kode_acara');
    }
}
