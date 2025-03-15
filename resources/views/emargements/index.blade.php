@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center" style="font-weight: 600; color: #1e3a8a;">Liste des émargements</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="text-end mb-3">
            <a href="{{ route(auth()->user()->role . '.emargements.create') }}" class="btn btn-primary" style="background-color: #3498db; border: none;">
                <i class="bi bi-plus-circle me-1"></i> Ajouter un émargement
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead style="background-color: #eef2ff; color: #1e3a8a;">
                        <tr>
                            <th>ID</th>
                            <th>Cours</th>
                            <th>Salle</th>
                            <th>Date</th>
                            <th>Statut</th>
                            @if (auth()->user()->role === 'admin')
                                <th>Description</th>
                                <th>Validé</th>
                            @endif
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($emargements as $emargement)
                            <tr>
                                <td>{{ $emargement->id }}</td>
                                <td>{{ $emargement->cours->nom ?? 'N/A' }}</td>
                                <td>{{ $emargement->cours->salle->libelle ?? 'N/A' }}</td>
                                <td>{{ $emargement->date->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $emargement->statut === 'présent' ? '#10b981' : ($emargement->statut === 'absent' ? '#ef4444' : '#f59e0b') }}; color: #fff;">
                                        {{ $emargement->statut === 'pending' ? 'En attente' : $emargement->statut }}
                                    </span>
                                </td>
                                @if (auth()->user()->role === 'admin')
                                    <td>{{ $emargement->description ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $emargement->valide_par_admin ? '#3498db' : '#f59e0b' }}; color: #fff;">
                                            {{ $emargement->valide_par_admin ? 'Oui' : 'Non' }}
                                        </span>
                                    </td>
                                @endif
                                <td>
                                    @if (auth()->user()->role === 'professeur')
                                        <a href="{{ route('professeur.emargements.show', $emargement->id) }}" class="btn btn-info btn-sm" style="background-color: #3b82f6; border: none;">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                    @else
                                        <a href="{{ route(auth()->user()->role . '.emargements.edit', $emargement->id) }}" class="btn btn-warning btn-sm" style="background-color: #f59e0b; border: none;">
                                            <i class="bi bi-pencil"></i> Modifier
                                        </a>
                                        <form action="{{ route(auth()->user()->role . '.emargements.destroy', $emargement->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" style="background-color: #ef4444; border: none;" onclick="return confirm('Voulez-vous vraiment supprimer cet émargement ?');">
                                                <i class="bi bi-trash"></i> Supprimer
                                            </button>
                                        </form>
                                        @if (auth()->user()->role === 'admin' && !$emargement->valide_par_admin)
                                            <form action="{{ route('admin.emargements.validate', $emargement->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <select name="statut" class="form-select form-select-sm d-inline-block" style="width: auto;">
                                                    <option value="présent">Présent</option>
                                                    <option value="absent">Absent</option>
                                                </select>
                                                <button type="submit" class="btn btn-success btn-sm" style="background-color: #10b981; border: none;" onclick="return confirm('Valider cet émargement ?');">
                                                    <i class="bi bi-check-circle"></i> Valider
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'admin' ? 8 : 6 }}" class="text-center text-muted py-4">Aucun émargement disponible.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
