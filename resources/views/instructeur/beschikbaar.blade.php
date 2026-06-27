@extends('layouts.app')

@section('title', 'Alle beschikbare voertuigen')

@section('content')
<div class="card">
    <div class="card-title">
        <span><i class="fa-solid fa-car"></i> Alle beschikbare voertuigen</span>
        <span class="badge badge-success">Aantal voertuigen: {{ $beschikbareVoertuigen->total() }}</span>
    </div>
    
    <div class="subtitle">
        Dit zijn de voertuigen die momenteel niet zijn toegewezen aan een actieve instructeur en ingezet kunnen worden.
    </div>

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
                </tr>
            </thead>
            <tbody>
                @forelse($beschikbareVoertuigen as $voertuig)
                    <tr>
                        <td><strong>{{ $voertuig->Kenteken }}</strong></td>
                        <td>{{ $voertuig->Type }}</td>
                        <td>{{ \Carbon\Carbon::parse($voertuig->Bouwjaar)->format('d-m-Y') }}</td>
                        <td>{{ $voertuig->Brandstof }}</td>
                        <td>{{ $voertuig->typeVoertuig->TypeVoertuig }}</td>
                        <td>
                            <span class="badge badge-success" style="background: rgba(56, 189, 248, 0.1); color: var(--accent-blue); border: 1px solid rgba(56, 189, 248, 0.2)">
                                {{ $voertuig->typeVoertuig->Rijbewijscategorie }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem;">Er zijn momenteel geen beschikbare voertuigen in de database.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        {{ $beschikbareVoertuigen->links() }}
    </div>
</div>
@endsection
