<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Voertuig;

class VoertuigTest extends TestCase
{
    public function test_vehicle_attributes_assignment(): void
    {
        $voertuig = new Voertuig([
            'Kenteken' => 'AU-67-IO',
            'Type' => 'Golf',
            'Brandstof' => 'Diesel'
        ]);

        $this->assertEquals('AU-67-IO', $voertuig->Kenteken);
        $this->assertEquals('Golf', $voertuig->Type);
        $this->assertEquals('Diesel', $voertuig->Brandstof);
    }
}
