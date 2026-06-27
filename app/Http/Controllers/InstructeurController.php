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
}
