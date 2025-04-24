<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tamu; // Import model Tamu

class TamusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tambahkan tamu pertama
        Tamu::create([
            'id_tamu' => '1001',
            'nama' => 'Arif Rohman',
            'asal_instansi' => 'Pemerintah Kabupaten Blora',
            'jabatan' => 'Bupati Blora',
        ]);

        // Tambahkan tamu kedua
        Tamu::create([
            'id_tamu' => '1002',
            'nama' => 'Riena R',
            'asal_instansi' => 'PPID Diskominfo Prov. Jateng',
            'jabatan' => 'Kepala Dinas Komunikasi dan Informatika', // Konsisten dengan nama kolom
        ]);
    }
}
