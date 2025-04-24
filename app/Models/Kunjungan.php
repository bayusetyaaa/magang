<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_tamu',
        'kode_acara',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'alasan_kunjungan',
    ];

    /**
     * Relasi dengan model Tamu
     * Asumsi kolom 'id_tamu' digunakan untuk relasi
     */
    public function tamu()
    {
        return $this->belongsTo(Tamu::class, 'id_tamu', 'id_tamu'); // Menentukan kolom 'id_tamu' untuk relasi
    }

    /**
     * Relasi dengan model Event (Acara)
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'kode_acara', 'kode_acara'); // Menentukan kolom 'kode_acara' untuk relasi
    }
}
