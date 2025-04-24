<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',         // Jika nip yang menjadi referensi, pastikan sesuai
        'kode_acara',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
    ];

    /**
     * Relasi dengan model Karyawan
     * Asumsi kolom 'nip' digunakan untuk relasi
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nip', 'nip'); // Menentukan kolom 'nip' di Presensi dan Karyawan
    }

    /**
     * Relasi dengan model Event
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'kode_acara', 'kode_acara'); // Menentukan kolom 'kode_acara' untuk relasi
    }

    /**
     * Jika Presensi memiliki relasi dengan Kunjungan, pastikan
     * bahwa ada kolom yang sesuai di tabel Presensi.
     */
    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class, 'kode_acara', 'kode_acara'); // Pastikan ini sesuai dengan relasi yang dimaksud
    }
}
