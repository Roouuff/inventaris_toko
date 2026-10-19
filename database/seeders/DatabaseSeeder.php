<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1 akun untuk pengujian login (password di-hash)
        User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@komputer.test',
            'password' => Hash::make('12345678'),
        ]);

        // 3 kategori
        $aksesori    = Category::create(['name' => 'Aksesori']);
        $perangkat   = Category::create(['name' => 'Perangkat Komputer']);
        $penyimpanan = Category::create(['name' => 'Penyimpanan']);

        // 5 barang, otomatis terhubung ke kategori yang valid lewat relasi
        $aksesori->items()->createMany([
            ['name' => 'Keyboard', 'price' => 150000, 'stock' => 10],
            ['name' => 'Mouse',    'price' => 75000,  'stock' => 20],
            ['name' => 'Headset',  'price' => 250000, 'stock' => 8],
        ]);

        $perangkat->items()->create(
            ['name' => 'Monitor', 'price' => 1500000, 'stock' => 5]
        );

        $penyimpanan->items()->create(
            ['name' => 'Flashdisk', 'price' => 85000, 'stock' => 15]
        );
    }
}