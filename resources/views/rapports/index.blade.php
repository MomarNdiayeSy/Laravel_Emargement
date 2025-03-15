@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center" style="font-weight: 600; color: #1e3a8a;">Rapports</h1>

        <!-- Formulaire de filtre dans une carte -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ auth()->user()->role === 'admin' ? route('admin.rapports.index') : (auth()->user()->role === 'professeur' ? route('professeur.rapports.index') : route('gestionnaire.rapports.index')) }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="date_debut" class="form-label">Date de début</label>
                        <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ request('date_debut') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_fin" class="form-label">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ request('date_fin') }}">
                    </div>
                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'gestionnaire')
                        <div class="col-md-3">
                            <label for="professeur_id" class="form-label">Professeur</label>
                            <select name="professeur_id" id="professeur_id" class="form-control">
                                <option value="">Tous les professeurs</option>
                                @foreach ($professeurs as $professeur)
                                    <option value="{{ $professeur->id }}" {{ request('professeur_id') == $professeur->id ? 'selected' : '' }}>
                                        {{ $professeur->prenom }} {{ $professeur->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100" style="background-color: #3498db; border: none;">Filtrer</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tableau des rapports dans une carte -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead style="background-color: #eef2ff; color: #1e3a8a;">
                        <tr>
                            <th>Date</th>
                            <th>Professeur</th>
                            <th>Cours</th>
                            <th>Statut</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($emargements as $emargement)
                            <tr>
                                <td>{{ $emargement->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $emargement->professeur->prenom }} {{ $emargement->professeur->nom }}</td>
                                <td>{{ $emargement->cours->nom }}</td>
                                <td>
                                        <span class="badge" style="background-color: {{ $emargement->statut === 'validé' ? '#10b981' : '#ef4444' }}; color: #fff;">
                                            {{ $emargement->statut }}
                                        </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Aucun émargement trouvé.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Boutons d'exportation -->
        <div class="mt-4 text-center">
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.rapports.export.pdf', request()->query()) : (auth()->user()->role === 'professeur' ? route('professeur.rapports.export.pdf', request()->query()) : route('gestionnaire.rapports.export.pdf', request()->query())) }}"
               class="btn btn-success me-2" style="background-color: #10b981; border: none;">
                Exporter en PDF
            </a>
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.rapports.export.excel', request()->query()) : (auth()->user()->role === 'professeur' ? route('professeur.rapports.export.excel', request()->query()) : route('gestionnaire.rapports.export.excel', request()->query())) }}"
               class="btn btn-success me-2" style="background-color: #10b981; border: none;">
                Exporter en Excel
            </a>
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.rapports.statistiques', request()->query()) : (auth()->user()->role === 'professeur' ? route('professeur.rapports.statistiques', request()->query()) : route('gestionnaire.rapports.statistiques', request()->query())) }}"
               class="btn btn-info" style="background-color: #3b82f6; border: none;">
                Voir les statistiques
            </a>
        </div>
    </div>
@endsection
