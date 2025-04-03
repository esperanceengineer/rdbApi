<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        User::factory()->admin()->create();
        User::factory()->representant()->create();
        User::factory()->representantExterne()->create();
        User::factory()->president()->create();
        
        User::factory()->consultant()->create([
            'username' => 'CONSULTANT_01',
            'name' => 'Anges Kevin Nzigou',
            'password' => Hash::make('CONSULTANT_01@2025'),
        ]);

        User::factory()->consultant()->create([
            'username' => 'CONSULTANT_02',
            'name' => 'Nora Kassa',
            'password' => Hash::make('CONSULTANT_02@2025'),
        ]);

        User::factory()->consultant()->create([
            'username' => 'CONSULTANT_03',
            'name' => 'Marc Ona Essangui',
            'password' => Hash::make('CONSULTANT_03@2025'),
        ]);

        User::factory()->consultant()->create([
            'username' => 'CONSULTANT_04',
            'name' => 'Luc Odiasse',
            'password' => Hash::make('CONSULTANT_04@2025'),
        ]);
    }
}
