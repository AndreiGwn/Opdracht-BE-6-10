<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InstructeurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('instructeurs')->delete();

        DB::table('instructeurs')->insert([
            [
                'Id' => 1,
                'Voornaam' => 'Li',
                'Tussenvoegsel' => 'Zhan',
                'Achternaam' => null,
                'Mobiel' => '06-28493827',
                'DatumInDienst' => '2015-04-17',
                'AantalSterren' => 3,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now(),
            ],
            [
                'Id' => 2,
                'Voornaam' => 'Leroy',
                'Tussenvoegsel' => null,
                'Achternaam' => 'Boerhaven',
                'Mobiel' => '06-39398734',
                'DatumInDienst' => '2018-06-25',
                'AantalSterren' => 1,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now(),
            ],
            [
                'Id' => 3,
                'Voornaam' => 'Yoeri',
                'Tussenvoegsel' => 'Van',
                'Achternaam' => 'Veen',
                'Mobiel' => '06-24383291',
                'DatumInDienst' => '2010-05-12',
                'AantalSterren' => 3,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now(),
            ],
            [
                'Id' => 4,
                'Voornaam' => 'Bert',
                'Tussenvoegsel' => 'Van',
                'Achternaam' => 'Sali',
                'Mobiel' => '06-48293823',
                'DatumInDienst' => '2023-01-10',
                'AantalSterren' => 4,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now(),
            ],
            [
                'Id' => 5,
                'Voornaam' => 'Mohammed',
                'Tussenvoegsel' => 'El',
                'Achternaam' => 'Yassidi',
                'Mobiel' => '06-34291234',
                'DatumInDienst' => '2010-06-14',
                'AantalSterren' => 5,
                'IsActief' => true,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now(),
            ],
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
