@extends('layouts.app')

@section('title', 'Instructeurs in dienst')

@section('content')
<div class="card">
    <div class="card-title">
        <span><i class="fa-solid fa-user-tie"></i> Instructeurs in dienst</span>
        <span class="badge badge-success">Aantal instructeurs: {{ $instructeurs->total() }}</span>
    </div>
    
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Voornaam</th>
                    <th>Tussenvoegsel</th>
                    <th>Achternaam</th>
                    <th>Mobiel</th>
                    <th>Datum in dienst</th>
                    <th>Aantal sterren</th>
                    <th style="text-align: center;">Status (Ziek/Verlof)</th>
                    <th style="text-align: center;">Wijzigen</th>
                    <th style="text-align: center;">Voertuigen</th>
                    <th style="text-align: center;">Verwijderen</th>
                </tr>
            </thead>
            <tbody>
                @forelse($instructeurs as $instructeur)
                    <tr>
                        <td>{{ $instructeur->Voornaam }}</td>
                        <td>{{ $instructeur->Tussenvoegsel ?? '-' }}</td>
                        <td>{{ $instructeur->Achternaam ?? '-' }}</td>
                        <td>{{ $instructeur->Mobiel }}</td>
                        <td>{{ \Carbon\Carbon::parse($instructeur->DatumInDienst)->format('d-m-Y') }}</td>
                        <td>
                            <div class="stars">
                                @for($i = 0; $i < $instructeur->AantalSterren; $i++)
                                    <i class="fa-solid fa-star"></i>
                                @endfor
                                @for($i = $instructeur->AantalSterren; $i < 5; $i++)
                                    <i class="fa-regular fa-star" style="opacity: 0.3;"></i>
                                @endfor
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <form action="{{ route('instructeur.toggle-status', $instructeur->Id) }}" method="POST" style="display: inline;">
                                @csrf
                                @if($instructeur->IsActief)
                                    <button type="submit" class="action-btn btn-toggle" title="Instructeur is actief. Klik om ziek/met verlof te melden.">
                                        <i class="fa-solid fa-thumbs-up"></i>
                                    </button>
                                @else
                                    <button type="submit" class="action-btn btn-toggle inactive" title="Instructeur is ziek/met verlof. Klik om beter/terug te melden.">
                                        <i class="fa-solid fa-band-aid"></i>
                                    </button>
                                @endif
                            </form>
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('instructeur.edit', $instructeur->Id) }}" class="action-btn btn-edit" title="Gegevens wijzigen">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('instructeur.voertuigen', $instructeur->Id) }}" class="action-btn" style="color: var(--accent-blue);" title="Toegewezen voertuigen">
                                <i class="fa-solid fa-car"></i>
                            </a>
                        </td>
                        <td style="text-align: center;">
                            <form action="{{ route('instructeur.destroy', $instructeur->Id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" title="Definitief verwijderen">
                                    <i class="fa-solid fa-xmark" style="font-size: 1.3rem; font-weight: bold;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 2rem;">Geen instructeurs gevonden in de database.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        {{ $instructeurs->links() }}
    </div>
</div>
@endsection
