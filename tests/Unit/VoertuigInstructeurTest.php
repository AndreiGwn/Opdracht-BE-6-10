<?php

namespace Tests\Unit;

use App\Models\VoertuigInstructeur;
use Tests\TestCase;

class VoertuigInstructeurTest extends TestCase
{
    public function test_voertuig_instructeur_attributes_assignment(): void
    {
        $pivot = new VoertuigInstructeur([
            'VoertuigId' => 1,
            'InstructeurId' => 5,
            'DatumToekenning' => '2017-06-18',
        ]);

        $this->assertEquals(1, $pivot->VoertuigId);
        $this->assertEquals(5, $pivot->InstructeurId);
        $this->assertEquals('2017-06-18', $pivot->DatumToekenning);
    }
}
