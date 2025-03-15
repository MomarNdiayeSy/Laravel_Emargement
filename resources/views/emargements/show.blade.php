@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center" style="font-weight: 600; color: #1e3a8a;">Détails de l'émargement</h1>

        <div class="card shadow-sm">
            <div class="card-header" style="background-color: #eef2ff; color: #1e3a8a;">
                <h5 class="card-title mb-0"><i class="bi bi-check-circle me-2"></i>Émargement #{{ $emargement->id }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="card-text">
                            <strong><i class="bi bi-person me-2"></i>Professeur :</strong>
                            <span>{{ $emargement->professeur->prenom }} {{ $emargement->professeur->nom }}</span>
                        </p>
                        <p class="card-text">
                            <strong><i class="bi bi-book me-2"></i>Cours :</strong>
                            <span>{{ $emargement->cours->nom }} ({{ $emargement->cours->heure_debut->format('d/m/Y H:i') }})</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="card-text">
                            <strong><i class="bi bi-check-circle-fill me-2"></i>Statut :</strong>
                            <span class="badge" style="background-color: {{ $emargement->statut === 'présent' ? '#10b981' : '#ef4444' }}; color: #fff;">
                                {{ $emargement->statut }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.emargements.edit', $emargement->id) }}" class="btn btn-warning me-2" style="background-color: #f59e0b; border: none;">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
            @endif
            <a href="{{ route(auth()->user()->role . '.emargements.index') }}" class="btn btn-secondary" style="background-color: #6b7280; border: none;">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>
@endsection
