<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::create([
            'kode_acara' => 'contoh_acara',
            'nama_acara' => 'Pagi Ceria',
            'tanggal' => '2025-01-03',
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'tempat' => 'Kantor Diskominfo Blora',
        ]);
    }
}