<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Tambahkan use statement untuk Hash
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tambahkan user dengan peran Admin
        User::create([
            'nip' => '123456789',
            'email' => 'bayu@gmail.com',
            'password' => Hash::make('bayu.123'), // Gunakan Hash dari Illuminate\Support\Facades
            'role' => 'admin', // Sesuaikan dengan kolom yang menandakan peran pada model User
        ]);

        // Tambahkan user lain jika diperlukan
        // User::create([
        //     'name' => 'Nama User',
        //     'email' => 'user@example.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'user',
        // ]);

        // Tambahkan kode seeder lainnya jika diperlukan
    }
}
