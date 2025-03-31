<?php

namespace Database\Seeders;

use App\Models\Provincy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvincySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Provincy::factory(10)->create();
    }
}
