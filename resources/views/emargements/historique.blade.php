@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center" style="font-weight: 600; color: #1e3a8a;">Historique des émargements</h1>

        <!-- Formulaire de filtre dans une carte -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route(auth()->user()->role . '.emargements.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="date_debut" class="form-label">Date de début</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                            <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ request('date_debut') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="date_fin" class="form-label">Date de fin</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                            <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ request('date_fin') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100" style="background-color: #3498db; border: none;">
                            <i class="bi bi-filter"></i> Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tableau dans une carte -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead style="background-color: #eef2ff; color: #1e3a8a;">
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Professeur</th>
                            <th>Cours</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($emargements as $emargement)
                            <tr>
                                <td>{{ $emargement->id }}</td>
                                <td>{{ $emargement->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                        <span class="badge" style="background-color: {{ $emargement->statut === 'présent' ? '#10b981' : '#ef4444' }}; color: #fff;">
                                            {{ $emargement->statut }}
                                        </span>
                                </td>
                                <td>{{ $emargement->professeur->prenom }} {{ $emargement->professeur->nom }}</td>
                                <td>{{ $emargement->cours->nom }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Aucun émargement trouvé.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
