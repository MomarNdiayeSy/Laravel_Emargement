@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center" style="font-weight: 600; color: #1e3a8a;">Ajouter un cours</h1>

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
                <form action="{{ route($role . '.cours.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom du cours</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-book"></i></span>
                                <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" required>
                            </div>
                            @error('nom')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="heure_debut" class="form-label">Heure de début</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                <input type="datetime-local" name="heure_debut" id="heure_debut" class="form-control" value="{{ old('heure_debut') }}" required>
                            </div>
                            @error('heure_debut')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="heure_fin" class="form-label">Heure de fin</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-clock-fill"></i></span>
                                <input type="datetime-local" name="heure_fin" id="heure_fin" class="form-control" value="{{ old('heure_fin') }}" required>
                            </div>
                            @error('heure_fin')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="salle_id" class="form-label">Salle</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <select name="salle_id" id="salle_id" class="form-control" required>
                                    <option value="">Sélectionner une salle</option>
                                    @foreach ($salles as $salle)
                                        <option value="{{ $salle->id }}" {{ old('salle_id') == $salle->id ? 'selected' : '' }}>
                                            {{ $salle->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('salle_id')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="professeur_id" class="form-label">Professeur</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <select name="professeur_id" id="professeur_id" class="form-control" required>
                                    <option value="">Sélectionner un professeur</option>
                                    @foreach ($professeurs as $professeur)
                                        <option value="{{ $professeur->id }}" {{ old('professeur_id') == $professeur->id ? 'selected' : '' }}>
                                            {{ $professeur->prenom }} {{ $professeur->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('professeur_id')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary me-2" style="background-color: #3498db; border: none;">
                            <i class="bi bi-save"></i> Créer
                        </button>
                        <a href="{{ route($role . '.cours.index') }}" class="btn btn-secondary" style="background-color: #6b7280; border: none;">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
