<?php

namespace Database\Seeders;

use App\Models\Candidate;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidates = [
            ['fullname' => 'Brice Clotaire Oligui Ngema','image' => 'OLIGUI.png'],
            ['fullname' => 'Alain Claude Bilie-By-Nze','image' => 'BILYE.png'],
            ['fullname' => 'Chaning Zénaba Gninga','image' => 'CHANING.png'],
            ['fullname' => "Thierry Yvon Michel N'goma",'image' => 'YVON.png'],
            ['fullname' => 'Alain Simplice Boungoueres','image' => 'SIMPLICE.png'],
            ['fullname' => 'Joseph Lapensée Essingone','image' => 'LAPENSE.png'],
            ['fullname' => 'Stéphane Germain Iloko Boussengui','image' => 'ILOKO.png'],
            ['fullname' => 'Axel Stophène Ibinga Ibinga','image' => 'AXEL.png'],
        ];

        foreach($candidates as $candidate) {
            Candidate::create($candidate);
        }
    }
}
