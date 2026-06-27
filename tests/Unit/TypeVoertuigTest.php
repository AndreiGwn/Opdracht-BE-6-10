<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\TypeVoertuig;

class TypeVoertuigTest extends TestCase
{
    public function test_type_voertuig_attributes_assignment(): void
    {
        $typeVoertuig = new TypeVoertuig([
            'TypeVoertuig' => 'Personenauto',
            'Rijbewijscategorie' => 'B'
        ]);

        $this->assertEquals('Personenauto', $typeVoertuig->TypeVoertuig);
        $this->assertEquals('B', $typeVoertuig->Rijbewijscategorie);
    }
}
