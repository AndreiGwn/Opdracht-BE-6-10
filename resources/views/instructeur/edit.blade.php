@extends('layouts.app')

@section('title', 'Instructeur Wijzigen')

@section('content')
<div class="card">
    <div class="card-title">
        <span><i class="fa-solid fa-pen-to-square"></i> Instructeur Gegevens Wijzigen</span>
        <a href="{{ route('instructeur.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Terug</a>
    </div>
    
    <form action="{{ route('instructeur.update', $instructeur->Id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label for="Voornaam" class="form-label">Voornaam</label>
                <input type="text" name="Voornaam" id="Voornaam" class="form-control" value="{{ old('Voornaam', $instructeur->Voornaam) }}" required>
            </div>
            
            <div class="form-group">
                <label for="Tussenvoegsel" class="form-label">Tussenvoegsel</label>
                <input type="text" name="Tussenvoegsel" id="Tussenvoegsel" class="form-control" value="{{ old('Tussenvoegsel', $instructeur->Tussenvoegsel) }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="Achternaam" class="form-label">Achternaam</label>
                <input type="text" name="Achternaam" id="Achternaam" class="form-control" value="{{ old('Achternaam', $instructeur->Achternaam) }}">
            </div>

            <div class="form-group">
                <label for="Mobiel" class="form-label">Mobiel nummer</label>
                <input type="text" name="Mobiel" id="Mobiel" class="form-control" value="{{ old('Mobiel', $instructeur->Mobiel) }}" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="DatumInDienst" class="form-label">Datum in dienst</label>
                <input type="date" name="DatumInDienst" id="DatumInDienst" class="form-control" value="{{ old('DatumInDienst', $instructeur->DatumInDienst) }}" required>
            </div>

            <div class="form-group">
                <label for="AantalSterren" class="form-label">Aantal sterren</label>
                <select name="AantalSterren" id="AantalSterren" class="form-control" required>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ old('AantalSterren', $instructeur->AantalSterren) == $i ? 'selected' : '' }}>
                            {{ $i }} {{ $i == 1 ? 'Ster' : 'Sterren' }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="Opmerking" class="form-label">Opmerking</label>
            <textarea name="Opmerking" id="Opmerking" class="form-control" rows="3" maxlength="250">{{ old('Opmerking', $instructeur->Opmerking) }}</textarea>
            <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.25rem; text-align: right;">Max. 250 tekens</div>
        </div>

        <div class="btn-container">
            <a href="{{ route('instructeur.index') }}" class="btn btn-secondary">Annuleren</a>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Opslaan</button>
        </div>
    </form>
</div>
@endsection
