<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //roda as seeders
        $this->call([CidadeSeeder::class]);
        $this->call([MedicoSeeder::class]);
        $this->call([PacienteSeeder::class]);
        $this->call([ConsultaSeeder::class]);
    }
}
