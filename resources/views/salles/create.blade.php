@extends('layouts.app')

@section('content')
    <div class="container">
        @if (auth()->user()->role !== 'admin')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Accès non autorisé. Seuls les administrateurs peuvent ajouter des salles.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @else
            <h1 class="mb-4 text-center" style="font-weight: 600; color: #1e3a8a;">Ajouter une salle</h1>

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
                    <form action="{{ route('admin.salles.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="libelle" class="form-label">Libellé</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <input type="text" name="libelle" id="libelle" class="form-control" value="{{ old('libelle') }}" required>
                                </div>
                                @error('libelle')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-primary me-2" style="background-color: #3498db; border: none;">
                                <i class="bi bi-save"></i> Créer
                            </button>
                            <a href="{{ route('admin.salles.index') }}" class="btn btn-secondary" style="background-color: #6b7280; border: none;">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
