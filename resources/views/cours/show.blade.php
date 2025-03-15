@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center" style="font-weight: 600; color: #1e3a8a;">Détails du cours</h1>

        <div class="card shadow-sm">
            <div class="card-header" style criatividade="background-color: #eef2ff; color: #1e3a8a;">
                <h5 class="card-title mb-0"><i class="bi bi-book me-2"></i>{{ $cours->nom }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="card-text">
                            <strong><i class="bi bi-card-text me-2"></i>Description :</strong>
                            <span>{{ $cours->description ?? 'N/A' }}</span>
                        </p>
                        <p class="card-text">
                            <strong><i class="bi bi-clock me-2"></i>Heure de début :</strong>
                            <span>{{ $cours->heure_debut->format('d/m/Y H:i') }}</span>
                        </p>
                        <p class="card-text">
                            <strong><i class="bi bi-clock-fill me-2"></i>Heure de fin :</strong>
                            <span>{{ $cours->heure_fin->format('d/m/Y H:i') }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="card-text">
                            <strong><i class="bi bi-building me-2"></i>Salle :</strong>
                            <span>{{ $cours->salle->libelle ?? 'N/A' }}</span>
                        </p>
                        <p class="card-text">
                            <strong><i class="bi bi-person me-2"></i>Professeur :</strong>
                            <span>{{ $cours->professeur->prenom ?? 'N/A' }} {{ $cours->professeur->nom ?? '' }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            @if ($role === 'admin' || $role === 'gestionnaire')
                <a href="{{ route($role . '.cours.edit', $cours->id) }}" class="btn btn-warning me-2" style="background-color: #f59e0b; border: none;">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
                <form action="{{ route($role . '.cours.destroy', $cours->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger me-2" style="background-color: #ef4444; border: none;" onclick="return confirm('Voulez-vous vraiment supprimer ce cours ?');">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            @endif
            <a href="{{ route($role . '.cours.index') }}" class="btn btn-secondary" style="background-color: #6b7280; border: none;">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>
@endsection
