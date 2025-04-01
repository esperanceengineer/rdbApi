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
        $this->call(CandidateSeeder::class);
        $this->call(ProvincySeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(LocalitySeeder::class);
        $this->call(CenterSeeder::class);
        $this->call(UserSeeder::class);
    }
}
