@extends('layouts.app')

@section('title', 'Gebruikte voertuigen')

@section('content')
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-title">
        <span><i class="fa-solid fa-id-card"></i> Instructeur Details</span>
        <a href="{{ route('instructeur.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Terug</a>
    </div>
    <div class="form-row">
        <div class="form-group">
            <span class="form-label" style="opacity: 0.7;">Naam</span>
            <strong style="font-size: 1.15rem; color: var(--text-primary);">{{ $instructeur->naam }}</strong>
        </div>
        <div class="form-group">
            <span class="form-label" style="opacity: 0.7;">Mobiel</span>
            <strong style="font-size: 1.15rem; color: var(--text-primary);">{{ $instructeur->Mobiel }}</strong>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <span class="form-label" style="opacity: 0.7;">Datum in dienst</span>
            <strong style="font-size: 1.15rem; color: var(--text-primary);">{{ \Carbon\Carbon::parse($instructeur->DatumInDienst)->format('d-m-Y') }}</strong>
        </div>
        <div class="form-group">
            <span class="form-label" style="opacity: 0.7;">Aantal sterren</span>
            <div class="stars" style="font-size: 1.15rem;">
                @for($i = 0; $i < $instructeur->AantalSterren; $i++)
                    <i class="fa-solid fa-star"></i>
                @endfor
                @for($i = $instructeur->AantalSterren; $i < 5; $i++)
                    <i class="fa-regular fa-star" style="opacity: 0.3;"></i>
                @endfor
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-title">
        <span><i class="fa-solid fa-car"></i> Door Instructeur gebruikte voertuigen</span>
        @if(!$instructeur->IsActief)
            <span class="badge badge-danger"><i class="fa-solid fa-band-aid"></i> Instructeur is Ziek/Met verlof</span>
        @endif
    </div>

    @if(!$instructeur->IsActief)
        <div style="padding: 2rem; text-align: center; color: var(--text-secondary);">
            <i class="fa-solid fa-circle-exclamation" style="font-size: 2rem; color: var(--accent-amber); margin-bottom: 1rem; display: block;"></i>
            <p>Deze instructeur is momenteel ziek of met verlof gemeld. Alle voertuigen zijn vrijgegeven en de lijst is leeg.</p>
        </div>
    @else
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Kenteken</th>
                        <th>Type</th>
                        <th>Bouwjaar</th>
                        <th>Brandstof</th>
                        <th>Type Voertuig</th>
                        <th>Rijbewijscategorie</th>
                        <th style="text-align: center;">Toegewezen</th>
                        <th style="text-align: center;">Vrijgeven / Actie</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paginatedVoertuigen as $record)
                        @php
                            $voertuig = $record->voertuig;
                        @endphp
                        <tr>
                            <td>{{ $voertuig->Kenteken }}</td>
                            <td>{{ $voertuig->Type }}</td>
                            <td>{{ \Carbon\Carbon::parse($voertuig->Bouwjaar)->format('d-m-Y') }}</td>
                            <td>{{ $voertuig->Brandstof }}</td>
                            <td>{{ $voertuig->typeVoertuig->TypeVoertuig }}</td>
                            <td>
                                <span class="badge badge-success" style="background: rgba(56, 189, 248, 0.1); color: var(--accent-blue); border: 1px solid rgba(56, 189, 248, 0.2);">
                                    {{ $voertuig->typeVoertuig->Rijbewijscategorie }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                @if($record->IsActief)
                                    <i class="fa-solid fa-circle-check" style="color: var(--accent-green); font-size: 1.3rem;" title="Toegewezen"></i>
                                @elseif($record->is_reassigned)
                                    <form action="{{ route('instructeur.voertuigen.reassign', ['instructeur_id' => $instructeur->Id, 'voertuig_id' => $voertuig->Id]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="action-btn" style="color: var(--accent-red); cursor: pointer;" title="Klik om dit voertuig weer toe te wijzen aan {{ $instructeur->naam }}">
                                            <i class="fa-solid fa-circle-xmark" style="font-size: 1.3rem;"></i>
                                        </button>
                                    </form>
                                @else
                                    <span style="color: var(--text-secondary); opacity: 0.5;">-</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($record->IsActief)
                                    <form action="{{ route('instructeur.voertuigen.release', ['instructeur_id' => $instructeur->Id, 'voertuig_id' => $voertuig->Id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Weet u zeker dat u de toewijzing van dit voertuig wilt intrekken?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn btn-delete" title="Toewijzing intrekken">
                                            <i class="fa-solid fa-link-slash"></i> Release
                                        </button>
                                    </form>
                                @else
                                    <span style="color: var(--text-secondary); opacity: 0.5;">Vrijgegeven</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem;">Geen voertuigen toegewezen aan deze instructeur.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $paginatedVoertuigen->links() }}
        </div>

        <!-- Assign New Vehicle section -->
        <div style="margin-top: 3rem; border-top: 1px solid var(--border-color); padding-top: 2rem;">
            <h3 style="margin-bottom: 1rem;"><i class="fa-solid fa-plus-circle"></i> Nieuw voertuig toewijzen</h3>
            @if($alleBeschikbareVoertuigen->isEmpty())
                <p style="color: var(--text-secondary);">Er zijn momenteel geen beschikbare voertuigen om toe te wijzen.</p>
            @else
                <form action="{{ route('instructeur.voertuigen.assign', $instructeur->Id) }}" method="POST" style="display: flex; gap: 1rem; align-items: flex-end; max-width: 600px;">
                    @csrf
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label for="VoertuigId" class="form-label">Kies een voertuig</label>
                        <select name="VoertuigId" id="VoertuigId" class="form-control" required>
                            <option value="">Selecteer een voertuig...</option>
                            @foreach($alleBeschikbareVoertuigen as $v)
                                <option value="{{ $v->Id }}">
                                    {{ $v->Kenteken }} - {{ $v->Type }} ({{ $v->typeVoertuig->TypeVoertuig }} - Cat. {{ $v->typeVoertuig->Rijbewijscategorie }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="height: 42px;"><i class="fa-solid fa-link"></i> Toewijzen</button>
                </form>
            @endif
        </div>
    @endif
</div>
@endsection
