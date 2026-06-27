<?php

namespace Tests\Feature;

use App\Models\Instructeur;
use App\Models\TypeVoertuig;
use App\Models\Voertuig;
use App\Models\VoertuigInstructeur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
            'Rijbewijscategorie' => 'B',
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
            'IsActief' => true,
        ]);

        // 2. Create vehicle and assign to instructor
        $voertuig = Voertuig::create([
            'Kenteken' => 'AU-67-IO',
            'Type' => 'Golf',
            'Bouwjaar' => '2017-06-12',
            'Brandstof' => 'Diesel',
            'TypeVoertuigId' => $this->typeVoertuig->Id,
            'IsActief' => true,
        ]);

        $assignment = VoertuigInstructeur::create([
            'VoertuigId' => $voertuig->Id,
            'InstructeurId' => $instructeur->Id,
            'DatumToekenning' => '2017-06-18',
            'IsActief' => true,
        ]);

        // 3. Post to toggle status route
        $response = $this->post(route('instructeur.toggle-status', $instructeur->Id));

        // 4. Assertions
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Instructeur Mohammed El Yassidi is ziek/met verlof gemeld');

        $instructeur->refresh();
        $assignment->refresh();

        $this->assertFalse((bool) $instructeur->IsActief);
        $this->assertFalse((bool) $assignment->IsActief);
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
            'IsActief' => false,
        ]);

        // 2. Create another active instructor (Bert)
        $bert = Instructeur::create([
            'Voornaam' => 'Bert',
            'Tussenvoegsel' => 'Van',
            'Achternaam' => 'Sali',
            'Mobiel' => '06-48293823',
            'DatumInDienst' => '2023-01-10',
            'AantalSterren' => 4,
            'IsActief' => true,
        ]);

        // 3. Create two vehicles that WERE assigned to Mohammed
        $v1 = Voertuig::create([
            'Kenteken' => 'AU-67-IO',
            'Type' => 'Golf',
            'Bouwjaar' => '2017-06-12',
            'Brandstof' => 'Diesel',
            'TypeVoertuigId' => $this->typeVoertuig->Id,
            'IsActief' => true,
        ]);

        $v2 = Voertuig::create([
            'Kenteken' => 'DRS-52-P',
            'Type' => 'Vespa',
            'Bouwjaar' => '2022-03-21',
            'Brandstof' => 'Benzine',
            'TypeVoertuigId' => $this->typeVoertuig->Id,
            'IsActief' => true,
        ]);

        // Mohammed's inactive assignments
        $mohammedV1 = VoertuigInstructeur::create([
            'VoertuigId' => $v1->Id,
            'InstructeurId' => $mohammed->Id,
            'DatumToekenning' => '2017-06-18',
            'IsActief' => false,
        ]);

        $mohammedV2 = VoertuigInstructeur::create([
            'VoertuigId' => $v2->Id,
            'InstructeurId' => $mohammed->Id,
            'DatumToekenning' => '2020-02-02',
            'IsActief' => false,
        ]);

        // 4. Reassign V1 to Bert during Mohammed's leave
        VoertuigInstructeur::create([
            'VoertuigId' => $v1->Id,
            'InstructeurId' => $bert->Id,
            'DatumToekenning' => '2026-01-01',
            'IsActief' => true,
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
        $this->assertTrue((bool) $mohammed->IsActief);

        // V2 (unclaimed) should be active for Mohammed again (Scenario 2)
        $this->assertTrue((bool) $mohammedV2->IsActief);

        // V1 (claimed by Bert) should remain inactive for Mohammed (Scenario 3)
        $this->assertFalse((bool) $mohammedV1->IsActief);
    }

    /**
     * Test Scenario 1 of Opdracht 10: Deleting active instructor succeeds and frees vehicles
     */
    public function test_delete_active_instructor_succeeds()
    {
        $instructeur = Instructeur::create([
            'Voornaam' => 'Mohammed',
            'Tussenvoegsel' => 'El',
            'Achternaam' => 'Yassidi',
            'Mobiel' => '06-34291234',
            'DatumInDienst' => '2010-06-14',
            'AantalSterren' => 5,
            'IsActief' => true,
        ]);

        $response = $this->delete(route('instructeur.destroy', $instructeur->Id));

        $response->assertRedirect(route('instructeur.index'));
        $response->assertSessionHas('success', 'Instructeur Mohammed El Yassidi is definitief verwijdert en al zijn eerder toegewezen voertuigen zijn vrijgegeven');

        $this->assertDatabaseMissing('instructeurs', ['Id' => $instructeur->Id]);
    }

    /**
     * Test Scenario 2 of Opdracht 10: Deleting inactive (on leave) instructor fails
     */
    public function test_delete_inactive_instructor_fails()
    {
        $instructeur = Instructeur::create([
            'Voornaam' => 'Mohammed',
            'Tussenvoegsel' => 'El',
            'Achternaam' => 'Yassidi',
            'Mobiel' => '06-34291234',
            'DatumInDienst' => '2010-06-14',
            'AantalSterren' => 5,
            'IsActief' => false,
        ]);

        $response = $this->delete(route('instructeur.destroy', $instructeur->Id));

        $response->assertRedirect(route('instructeur.index'));
        $response->assertSessionHas('error', 'Instructeur Mohammed El Yassidi kan niet definitief worden verwijderd, verander eerst de status ziekte/verlof');

        $this->assertDatabaseHas('instructeurs', ['Id' => $instructeur->Id]);
    }

    /**
     * Test: Deleting an active vehicle assignment from instructor's list succeeds
     */
    public function test_delete_active_vehicle_assignment_succeeds()
    {
        $instructeur = Instructeur::create([
            'Voornaam' => 'Mohammed',
            'Mobiel' => '06-34291234',
            'DatumInDienst' => '2010-06-14',
            'AantalSterren' => 5,
            'IsActief' => true,
        ]);

        $voertuig = Voertuig::create([
            'Kenteken' => 'AU-67-IO',
            'Type' => 'Golf',
            'Bouwjaar' => '2017-06-12',
            'Brandstof' => 'Diesel',
            'TypeVoertuigId' => $this->typeVoertuig->Id,
            'IsActief' => true,
        ]);

        $assignment = VoertuigInstructeur::create([
            'VoertuigId' => $voertuig->Id,
            'InstructeurId' => $instructeur->Id,
            'DatumToekenning' => '2017-06-18',
            'IsActief' => true,
        ]);

        $response = $this->delete(route('instructeur.voertuigen.release', [
            'instructeur_id' => $instructeur->Id,
            'voertuig_id' => $voertuig->Id,
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Voertuig succesvol verwijderd.');
        $this->assertDatabaseMissing('voertuig_instructeurs', ['Id' => $assignment->Id]);
    }

    /**
     * Test: Deleting an inactive vehicle assignment from instructor's list fails
     */
    public function test_delete_inactive_vehicle_assignment_fails()
    {
        $instructeur = Instructeur::create([
            'Voornaam' => 'Mohammed',
            'Mobiel' => '06-34291234',
            'DatumInDienst' => '2010-06-14',
            'AantalSterren' => 5,
            'IsActief' => true,
        ]);

        $voertuig = Voertuig::create([
            'Kenteken' => 'AU-67-IO',
            'Type' => 'Golf',
            'Bouwjaar' => '2017-06-12',
            'Brandstof' => 'Diesel',
            'TypeVoertuigId' => $this->typeVoertuig->Id,
            'IsActief' => true,
        ]);

        $assignment = VoertuigInstructeur::create([
            'VoertuigId' => $voertuig->Id,
            'InstructeurId' => $instructeur->Id,
            'DatumToekenning' => '2017-06-18',
            'IsActief' => false,
        ]);

        $response = $this->delete(route('instructeur.voertuigen.release', [
            'instructeur_id' => $instructeur->Id,
            'voertuig_id' => $voertuig->Id,
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Dit voertuig is niet actief en kan niet worden verwijderd van de lijst.');
        $this->assertDatabaseHas('voertuig_instructeurs', ['Id' => $assignment->Id]);
    }
}
