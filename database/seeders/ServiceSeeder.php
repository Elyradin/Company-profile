<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service; // <--- PASTIKAN INI ADA

class ServiceSeeder extends Seeder
{
    public function run()
    {
        // Contoh buat data dummy
        Service::create([
            'name' => 'Web Development',
            'description' => 'Jasa pembuatan website profesional',
        ]);

        Service::create([
            'name' => 'Mobile Apps',
            'description' => 'Jasa pembuatan aplikasi Android & iOS',
        ]);
    }
}