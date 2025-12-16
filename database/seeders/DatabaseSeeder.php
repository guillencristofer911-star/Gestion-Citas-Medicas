<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Admin
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('password123')
        ]);
        
        // Usuarios pacientes
        \App\Models\User::factory(5)->create([
            'role' => 'patient'
        ]);

        // Ejecutar seeders
        $this->call([
            DoctorSeeder::class,
            ScheduleSeeder::class,
            AppointmentSeeder::class,
        ]);
    }
}
