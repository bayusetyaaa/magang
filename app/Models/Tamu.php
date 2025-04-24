<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tamu extends Model
{
    use HasFactory;

    protected $table = 'tamus'; // Pastikan nama tabel ini sesuai dengan nama tabel di database
    protected $fillable = [
        'id_tamu',
        'nama',
        'asal_instansi',
        'jabatan',
    ];

    // Relasi ke model Kunjungan
    public function kunjungan()
    {
        return $this->hasMany(Kunjungan::class, 'id_tamu', 'id_tamu');
    }

    // Relasi ke model TamuAcara
    public function tamuAcara()
    {
        return $this->hasMany(TamuAcara::class, 'id_tamu', 'id_tamu');
    }

    // Event model untuk menangani penghapusan
    protected static function boot()
    {
        parent::boot();

        // Tambahkan event penghapusan jika diperlukan
        static::deleted(function ($tamu) {
            // Contoh: Hapus semua relasi di TamuAcara saat tamu dihapus
            $tamu->tamuAcara()->delete();
        });
    }
}
