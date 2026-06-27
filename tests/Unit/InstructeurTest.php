<?php

namespace Tests\Unit;

use App\Models\Instructeur;
use Tests\TestCase;

class InstructeurTest extends TestCase
{
    public function test_instructor_full_name_accessor_without_tussenvoegsel(): void
    {
        $instructeur = new Instructeur([
            'Voornaam' => 'Leroy',
            'Tussenvoegsel' => null,
            'Achternaam' => 'Boerhaven',
        ]);

        $this->assertEquals('Leroy Boerhaven', $instructeur->naam);
    }

    public function test_instructor_full_name_accessor_with_tussenvoegsel(): void
    {
        $instructeur = new Instructeur([
            'Voornaam' => 'Yoeri',
            'Tussenvoegsel' => 'Van',
            'Achternaam' => 'Veen',
        ]);

        $this->assertEquals('Yoeri Van Veen', $instructeur->naam);
    }
}
