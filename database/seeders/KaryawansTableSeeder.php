<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Karyawan; // Import model Karyawan

class KaryawansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Karyawan::create([
            'nip' => '123456789',
            'nama' => 'Bayu',
            'divisi' => 'Teknologi Informasi',
            'jabatan' => 'Magang',
            'alamat' => 'Ds. Karang Kec. Bogorejo Kab. Blora',
            'no_hp' => '081234567890',
        ]);

        Karyawan::create([
            'nip' => '987654321',
            'nama' => 'Ilham',
            'divisi' => 'Teknologi Informasi',
            'jabatan' => 'Magang',
            'alamat' => 'Ds. Banjarejo Kec. Blora Kab. Blora',
            'no_hp' => '082345678901',
        ]);

    }
}
