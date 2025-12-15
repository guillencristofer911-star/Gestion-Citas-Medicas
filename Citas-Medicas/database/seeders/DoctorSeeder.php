<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            [
                'user' => [
                    'name' => 'Dr. Carlos Pérez',
                    'email' => 'carlos.perez@hospital.com',
                    'password' => Hash::make('password123'),
                    'role' => 'doctor',
                    'active' => true,
                ],
                'doctor' => [
                    'license_number' => 'MED-001',
                    'specialty' => 'Cardiología',
                    'biography' => 'Especialista en cardiología con 10 años de experiencia.',
                    'active' => true,
                ]
            ],
            [
                'user' => [
                    'name' => 'Dra. María González',
                    'email' => 'maria.gonzalez@hospital.com',
                    'password' => Hash::make('password123'),
                    'role' => 'doctor',
                    'active' => true,
                ],
                'doctor' => [
                    'license_number' => 'MED-002',
                    'specialty' => 'Pediatría',
                    'biography' => 'Pediatra con amplia experiencia en el cuidado infantil.',
                    'active' => true,
                ]
            ],
            [
                'user' => [
                    'name' => 'Dr. Juan Rodríguez',
                    'email' => 'juan.rodriguez@hospital.com',
                    'password' => Hash::make('password123'),
                    'role' => 'doctor',
                    'active' => true,
                ],
                'doctor' => [
                    'license_number' => 'MED-003',
                    'specialty' => 'Traumatología',
                    'biography' => 'Traumatólogo especializado en lesiones deportivas.',
                    'active' => true,
                ]
            ]
        ];

        foreach ($doctors as $doctorData) {
            // Crear el usuario primero
            $user = User::create($doctorData['user']);
            
            // Luego crear el perfil de doctor
            Doctor::create([
                'user_id' => $user->id,
                'license_number' => $doctorData['doctor']['license_number'],
                'specialty' => $doctorData['doctor']['specialty'],
                'biography' => $doctorData['doctor']['biography'],
                'active' => $doctorData['doctor']['active'],
            ]);
        }
    }
}
