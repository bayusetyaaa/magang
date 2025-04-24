<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Karyawan extends Model
{
    protected $fillable = [
        'nip', 'nama', 'divisi', 'jabatan', 'alamat', 'no_hp',
    ];

    // App\Models\Karyawan.php
    public function user()
    {
        return $this->hasOne(User::class, 'nip', 'nip'); // foreignKey = nip, localKey = nip
    }

   // Relationship dengan Presensi
   public function presensi()
   {
       return $this->hasMany(Presensi::class, 'nip', 'nip');
   }

   // Event model untuk menangani penghapusan
   protected static function boot()
   {
       parent::boot();

       static::deleted(function($karyawan) {
           $karyawan->presensi()->delete();
           $karyawan->user()->delete();
       });
   }






}

