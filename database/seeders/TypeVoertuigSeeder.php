<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeVoertuigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type_voertuigs')->insert([
            [
                'Id' => 1,
                'TypeVoertuig' => 'Personenauto',
                'Rijbewijscategorie' => 'B',
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now()
            ],
            [
                'Id' => 2,
                'TypeVoertuig' => 'Vrachtwagen',
                'Rijbewijscategorie' => 'C',
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now()
            ],
            [
                'Id' => 3,
                'TypeVoertuig' => 'Bus',
                'Rijbewijscategorie' => 'D',
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now()
            ],
            [
                'Id' => 4,
                'TypeVoertuig' => 'Bromfiets',
                'Rijbewijscategorie' => 'AM',
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now()
            ]
        ]);
    }
}
