@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center" style="font-weight: 600; color: #1e3a8a;">Modifier un émargement</h1>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route(auth()->user()->role . '.emargements.update', $emargement->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        @if (auth()->user()->role === 'admin' || auth()->user()->role === 'gestionnaire')
                            <div class="col-md-12">
                                <label for="cours_professeur" class="form-label">Cours et Professeur</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-book"></i></span>
                                    <select name="cours_professeur" id="cours_professeur" class="form-control" required>
                                        <option value="">Sélectionnez un cours et professeur</option>
                                        @foreach ($coursProfesseurOptions as $option)
                                            <option value="{{ $option['value'] }}" {{ old('cours_professeur', "{$emargement->professeur_id}-{$emargement->cours_id}") == $option['value'] ? 'selected' : '' }}>
                                                {{ $option['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('cours_professeur')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        @else
                            <div class="col-md-12">
                                <label for="cours_id" class="form-label">Cours</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-book"></i></span>
                                    <select name="cours_id" id="cours_id" class="form-control" required>
                                        <option value="">Sélectionnez un cours</option>
                                        @foreach ($cours as $cour)
                                            <option value="{{ $cour->id }}" {{ old('cours_id', $emargement->cours_id) == $cour->id ? 'selected' : '' }}>
                                                {{ $cour->nom }} — Salle {{ $cour->salle->libelle ?? 'N/A' }} — {{ $cour->heure_debut->format('d/m/Y H:i') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('cours_id')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <div class="col-md-12">
                            <label for="statut" class="form-label">Statut</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-check-circle"></i></span>
                                <select name="statut" id="statut" class="form-control" required>
                                    <option value="">Sélectionnez un statut</option>
                                    <option value="présent" {{ old('statut', $emargement->statut) == 'présent' ? 'selected' : '' }}>Présent</option>
                                    <option value="absent" {{ old('statut', $emargement->statut) == 'absent' ? 'selected' : '' }}>Absent</option>
                                </select>
                            </div>
                            @error('statut')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        @if (auth()->user()->role === 'admin' && $emargement->description)
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description (générée automatiquement)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                    <input type="text" class="form-control" id="description" value="{{ $emargement->description }}" disabled>
                                </div>
                                <small class="form-text text-muted">La description sera mise à jour automatiquement après soumission.</small>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary me-2" style="background-color: #3498db; border: none;">
                            <i class="bi bi-save"></i> Mettre à jour
                        </button>
                        <a href="{{ route(auth()->user()->role . '.emargements.index') }}" class="btn btn-secondary" style="background-color: #6b7280; border: none;">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
