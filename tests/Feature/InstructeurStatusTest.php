<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Instructeur;
use App\Models\Voertuig;
use App\Models\VoertuigInstructeur;
use App\Models\TypeVoertuig;

class InstructeurStatusTest extends TestCase
{
    use RefreshDatabase;

    private $typeVoertuig;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a base type vehicle for testing
        $this->typeVoertuig = TypeVoertuig::create([
            'TypeVoertuig' => 'Personenauto',
            'Rijbewijscategorie' => 'B'
        ]);
    }

    /**
     * Test Scenario 1: Deactivating an instructor releases their vehicles
     */
    public function test_deactivate_instructor_releases_vehicles()
    {
        // 1. Create active instructor
        $instructeur = Instructeur::create([
            'Voornaam' => 'Mohammed',
            'Tussenvoegsel' => 'El',
            'Achternaam' => 'Yassidi',
            'Mobiel' => '06-34291234',
            'DatumInDienst' => '2010-06-14',
            'AantalSterren' => 5,
            'IsActief' => true
        ]);

        // 2. Create vehicle and assign to instructor
        $voertuig = Voertuig::create([
            'Kenteken' => 'AU-67-IO',
            'Type' => 'Golf',
            'Bouwjaar' => '2017-06-12',
            'Brandstof' => 'Diesel',
            'TypeVoertuigId' => $this->typeVoertuig->Id,
            'IsActief' => true
        ]);

        $assignment = VoertuigInstructeur::create([
            'VoertuigId' => $voertuig->Id,
            'InstructeurId' => $instructeur->Id,
            'DatumToekenning' => '2017-06-18',
            'IsActief' => true
        ]);

        // 3. Post to toggle status route
        $response = $this->post(route('instructeur.toggle-status', $instructeur->Id));

        // 4. Assertions
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Instructeur Mohammed El Yassidi is ziek/met verlof gemeld');

        $instructeur->refresh();
        $assignment->refresh();

        $this->assertFalse((bool)$instructeur->IsActief);
        $this->assertFalse((bool)$assignment->IsActief);
    }

    /**
     * Test Scenario 2 & 3: Reactivating instructor restores unclaimed vehicles
     * but handles reassigned vehicles with red cross (remains inactive for them)
     */
    public function test_reactivate_instructor_restores_unclaimed_only()
    {
        // 1. Create inactive instructor (Mohammed)
        $mohammed = Instructeur::create([
            'Voornaam' => 'Mohammed',
            'Tussenvoegsel' => 'El',
            'Achternaam' => 'Yassidi',
            'Mobiel' => '06-34291234',
            'DatumInDienst' => '2010-06-14',
            'AantalSterren' => 5,
            'IsActief' => false
        ]);

        // 2. Create another active instructor (Bert)
        $bert = Instructeur::create([
            'Voornaam' => 'Bert',
            'Tussenvoegsel' => 'Van',
            'Achternaam' => 'Sali',
            'Mobiel' => '06-48293823',
            'DatumInDienst' => '2023-01-10',
            'AantalSterren' => 4,
            'IsActief' => true
        ]);

        // 3. Create two vehicles that WERE assigned to Mohammed
        $v1 = Voertuig::create([
            'Kenteken' => 'AU-67-IO',
            'Type' => 'Golf',
            'Bouwjaar' => '2017-06-12',
            'Brandstof' => 'Diesel',
            'TypeVoertuigId' => $this->typeVoertuig->Id,
            'IsActief' => true
        ]);

        $v2 = Voertuig::create([
            'Kenteken' => 'DRS-52-P',
            'Type' => 'Vespa',
            'Bouwjaar' => '2022-03-21',
            'Brandstof' => 'Benzine',
            'TypeVoertuigId' => $this->typeVoertuig->Id,
            'IsActief' => true
        ]);

        // Mohammed's inactive assignments
        $mohammedV1 = VoertuigInstructeur::create([
            'VoertuigId' => $v1->Id,
            'InstructeurId' => $mohammed->Id,
            'DatumToekenning' => '2017-06-18',
            'IsActief' => false
        ]);

        $mohammedV2 = VoertuigInstructeur::create([
            'VoertuigId' => $v2->Id,
            'InstructeurId' => $mohammed->Id,
            'DatumToekenning' => '2020-02-02',
            'IsActief' => false
        ]);

        // 4. Reassign V1 to Bert during Mohammed's leave
        VoertuigInstructeur::create([
            'VoertuigId' => $v1->Id,
            'InstructeurId' => $bert->Id,
            'DatumToekenning' => '2026-01-01',
            'IsActief' => true
        ]);

        // 5. Post to toggle status route (reactivate Mohammed)
        $response = $this->post(route('instructeur.toggle-status', $mohammed->Id));

        // 6. Assertions
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Instructeur Mohammed El Yassidi is beter/terug van verlof gemeld');

        $mohammed->refresh();
        $mohammedV1->refresh();
        $mohammedV2->refresh();

        // Mohammed should be active now
        $this->assertTrue((bool)$mohammed->IsActief);

        // V2 (unclaimed) should be active for Mohammed again (Scenario 2)
        $this->assertTrue((bool)$mohammedV2->IsActief);

        // V1 (claimed by Bert) should remain inactive for Mohammed (Scenario 3)
        $this->assertFalse((bool)$mohammedV1->IsActief);
    }
}
