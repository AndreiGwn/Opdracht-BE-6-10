<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Instructeur;
use App\Models\Voertuig;
use App\Models\VoertuigInstructeur;
use App\Models\TypeVoertuig;

class InstructeurController extends Controller
{
    public function index()
    {
        // Instructeurs in dienst, gesorteerd op sterren (descending) met paginering van 4 items
        $instructeurs = Instructeur::orderBy('AantalSterren', 'desc')->paginate(4);
        return view('instructeur.index', compact('instructeurs'));
    }

    public function edit($id)
    {
        $instructeur = Instructeur::findOrFail($id);
        return view('instructeur.edit', compact('instructeur'));
    }

    public function update(Request $request, $id)
    {
        $instructeur = Instructeur::findOrFail($id);

        $validated = $request->validate([
            'Voornaam' => 'required|string|max:50',
            'Tussenvoegsel' => 'nullable|string|max:20',
            'Achternaam' => 'nullable|string|max:50',
            'Mobiel' => 'required|string|max:20',
            'DatumInDienst' => 'required|date',
            'AantalSterren' => 'required|integer|min:1|max:5',
            'Opmerking' => 'nullable|string|max:250',
        ]);

        $instructeur->update($validated);

        return redirect()
            ->route('instructeur.index')
            ->with('success', "Instructeur {$instructeur->naam} is succesvol bijgewerkt.");
    }

    public function voertuigen($id)
    {
        $instructeur = Instructeur::findOrFail($id);

        if (!$instructeur->IsActief) {
            // Inactive instructor has empty list
            $paginatedVoertuigen = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 4);
        } else {
            // Find all assignments for this instructor (active and inactive)
            $pivotRecords = VoertuigInstructeur::where('InstructeurId', $id)
                ->with('voertuig.typeVoertuig')
                ->paginate(4);

            // Determine if each assignment is currently reassigned to someone else
            foreach ($pivotRecords as $record) {
                $record->is_reassigned = false;
                if (!$record->IsActief) {
                    $activeForSomeoneElse = VoertuigInstructeur::where('VoertuigId', $record->VoertuigId)
                        ->where('IsActief', true)
                        ->where('InstructeurId', '!=', $id)
                        ->exists();
                    if ($activeForSomeoneElse) {
                        $record->is_reassigned = true;
                    }
                }
            }
            $paginatedVoertuigen = $pivotRecords;
        }

        // Fetch list of all available vehicles to allow manual assignment
        $activeVoertuigIds = VoertuigInstructeur::where('IsActief', true)
            ->whereHas('instructeur', function ($query) {
                $query->where('IsActief', true);
            })
            ->pluck('VoertuigId');

        $alleBeschikbareVoertuigen = Voertuig::whereNotIn('Id', $activeVoertuigIds)
            ->with('typeVoertuig')
            ->get();

        return view('instructeur.voertuigen', compact('instructeur', 'paginatedVoertuigen', 'alleBeschikbareVoertuigen'));
    }

    public function beschikbaarVoertuigen()
    {
        // Get all active assignments
        $activeVoertuigIds = VoertuigInstructeur::where('IsActief', true)
            ->whereHas('instructeur', function ($query) {
                $query->where('IsActief', true);
            })
            ->pluck('VoertuigId');

        // Available vehicles are those not currently active for any active instructor
        $beschikbareVoertuigen = Voertuig::whereNotIn('Id', $activeVoertuigIds)
            ->with('typeVoertuig')
            ->paginate(4);

        return view('instructeur.beschikbaar', compact('beschikbareVoertuigen'));
    }

    public function assignVoertuig(Request $request, $instructeur_id)
    {
        $request->validate([
            'VoertuigId' => 'required|exists:voertuigs,Id'
        ]);

        $instructeur = Instructeur::findOrFail($instructeur_id);

        if (!$instructeur->IsActief) {
            return redirect()->back()->with('error', 'Kan geen voertuig toewijzen aan een inactieve instructeur.');
        }

        // Deactivate any existing active assignment for this vehicle
        VoertuigInstructeur::where('VoertuigId', $request->VoertuigId)
            ->where('IsActief', true)
            ->update(['IsActief' => false]);

        // Create or update the assignment for this instructor
        VoertuigInstructeur::updateOrCreate(
            ['VoertuigId' => $request->VoertuigId, 'InstructeurId' => $instructeur_id],
            ['IsActief' => true, 'DatumToekenning' => now()]
        );

        return redirect()->back()->with('success', 'Voertuig succesvol toegewezen.');
    }

    public function releaseVoertuig($instructeur_id, $voertuig_id)
    {
        $assignment = VoertuigInstructeur::where('VoertuigId', $voertuig_id)
            ->where('InstructeurId', $instructeur_id)
            ->firstOrFail();

        $assignment->update(['IsActief' => false]);

        return redirect()->back()->with('success', 'Voertuig succesvol vrijgegeven.');
    }

    public function toggleStatus($id)
    {
        $instructeur = Instructeur::findOrFail($id);

        if ($instructeur->IsActief) {
            // Scenario 1: Deactivate instructor due to illness/leave
            $instructeur->IsActief = false;
            $instructeur->save();

            // Deactivate all active assignments for this instructor to release their vehicles
            VoertuigInstructeur::where('InstructeurId', $id)
                ->where('IsActief', true)
                ->update(['IsActief' => false]);

            $message = "Instructeur {$instructeur->naam} is ziek/met verlof gemeld";
        } else {
            // Scenario 2 & 3: Reactivate instructor
            $instructeur->IsActief = true;
            $instructeur->save();

            // Find all previously deactivated assignments for this instructor
            $inactiveAssignments = VoertuigInstructeur::where('InstructeurId', $id)
                ->where('IsActief', false)
                ->get();

            foreach ($inactiveAssignments as $assignment) {
                // Check if vehicle was reassigned to another active instructor
                $reassigned = VoertuigInstructeur::where('VoertuigId', $assignment->VoertuigId)
                    ->where('IsActief', true)
                    ->where('InstructeurId', '!=', $id)
                    ->exists();

                if (!$reassigned) {
                    // Scenario 2: If NOT reassigned, reactivate this vehicle assignment
                    $assignment->update(['IsActief' => true]);
                }
                // Scenario 3: If reassigned, keep IsActief = false so it gets a red cross
            }

            $message = "Instructeur {$instructeur->naam} is beter/terug van verlof gemeld";
        }

        return redirect()->back()->with('success', $message);
    }

    public function reassignVoertuig($instructeur_id, $voertuig_id)
    {
        $instructeur = Instructeur::findOrFail($instructeur_id);

        if (!$instructeur->IsActief) {
            return redirect()->back()->with('error', 'Kan geen voertuig toewijzen aan een inactieve instructeur.');
        }

        // Deactivate other active assignments for this vehicle
        VoertuigInstructeur::where('VoertuigId', $voertuig_id)
            ->where('IsActief', true)
            ->update(['IsActief' => false]);

        // Reactivate Mohammed's (or this instructor's) assignment
        VoertuigInstructeur::updateOrCreate(
            ['VoertuigId' => $voertuig_id, 'InstructeurId' => $instructeur_id],
            ['IsActief' => true, 'DatumToekenning' => now()]
        );

        return redirect()->back()->with('success', "Het geselecteerde voertuig is weer toegewezen aan {$instructeur->naam}");
    }

    public function destroy($id)
    {
        $instructeur = Instructeur::findOrFail($id);

        if (!$instructeur->IsActief) {
            // Scenario 2 (Unhappy Path): Cannot delete if on leave (band-aid status)
            return redirect()
                ->route('instructeur.index')
                ->with('error', "Instructeur {$instructeur->naam} kan niet definitief worden verwijderd, verander eerst de status ziekte/verlof");
        }

        // Scenario 1 (Happy Path): Delete instructor and free vehicles
        $naam = $instructeur->naam;
        $instructeur->delete();

        return redirect()
            ->route('instructeur.index')
            ->with('success', "Instructeur {$naam} is definitief verwijdert en al zijn eerder toegewezen voertuigen zijn vrijgegeven");
    }
}
