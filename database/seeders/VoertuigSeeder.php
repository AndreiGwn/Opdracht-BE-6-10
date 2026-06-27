<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoertuigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        DB::table('voertuigs')->insert([
            [
                'Id' => 1,
                'Kenteken' => 'AU-67-IO',
                'Type' => 'Golf',
                'Bouwjaar' => '2017-06-12',
                'Brandstof' => 'Diesel',
                'TypeVoertuigId' => 1,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now()
            ],
            [
                'Id' => 2,
                'Kenteken' => 'TR-24-OP',
                'Type' => 'DAF',
                'Bouwjaar' => '2019-05-23',
                'Brandstof' => 'Diesel',
                'TypeVoertuigId' => 2,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now()
            ],
            [
                'Id' => 3,
                'Kenteken' => 'TH-78-KL',
                'Type' => 'Mercedes',
                'Bouwjaar' => '2023-01-01',
                'Brandstof' => 'Benzine',
                'TypeVoertuigId' => 1,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now()
            ],
            [
                'Id' => 4,
                'Kenteken' => '90-KL-TR',
                'Type' => 'Fiat 500',
                'Bouwjaar' => '2021-09-12',
                'Brandstof' => 'Benzine',
                'TypeVoertuigId' => 1,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now()
            ],
            [
                'Id' => 5,
                'Kenteken' => '34-TK-LP',
                'Type' => 'Scania',
                'Bouwjaar' => '2015-03-13',
                'Brandstof' => 'Diesel',
                'TypeVoertuigId' => 2,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now()
            ],
            [
                'Id' => 6,
                'Kenteken' => 'YY-OP-78',
                'Type' => 'BMW M5',
                'Bouwjaar' => '2022-05-13',
                'Brandstof' => 'Diesel',
                'TypeVoertuigId' => 1,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now()
            ],
            [
                'Id' => 7,
                'Kenteken' => 'UU-HH-JK',
                'Type' => 'M.A.N',
                'Bouwjaar' => '2017-12-03',
                'Brandstof' => 'Diesel',
                'TypeVoertuigId' => 2,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now()
            ],
            [
                'Id' => 8,
                'Kenteken' => 'ST-FZ-28',
                'Type' => 'Citroën',
                'Bouwjaar' => '2018-01-20',
                'Brandstof' => 'Elektrisch',
                'TypeVoertuigId' => 1,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now()
            ],
            [
                'Id' => 9,
                'Kenteken' => '123-FR-T',
                'Type' => 'Piaggio ZIP',
                'Bouwjaar' => '2021-02-01',
                'Brandstof' => 'Benzine',
                'TypeVoertuigId' => 4,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now()
            ],
            [
                'Id' => 10,
                'Kenteken' => 'DRS-52-P',
                'Type' => 'Vespa',
                'Bouwjaar' => '2022-03-21',
                'Brandstof' => 'Benzine',
                'TypeVoertuigId' => 4,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now()
            ],
            [
                'Id' => 11,
                'Kenteken' => 'STP-12-U',
                'Type' => 'Kymco',
                'Bouwjaar' => '2022-07-02',
                'Brandstof' => 'Benzine',
                'TypeVoertuigId' => 4,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now()
            ],
            [
                'Id' => 12,
                'Kenteken' => '45-SD-23',
                'Type' => 'Renault',
                'Bouwjaar' => '2023-01-01',
                'Brandstof' => 'Diesel',
                'TypeVoertuigId' => 3,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now()
            ]
        ]);
}
