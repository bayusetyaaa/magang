<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_acara', 'nama_acara', 'tanggal', 'jam_mulai', 'jam_selesai', 'tempat',
    ];

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    // Relationship dengan Kunjungan
    public function kunjungan()
    {
        return $this->hasOne(Kunjungan::class);
    }

    // Event model untuk menangani penghapusan
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($event) {
        });
    }
    public function tamuAcara()
    {
        return $this->hasMany(TamuAcara::class);
    }
}