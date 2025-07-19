@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center" style="font-weight: 600; color: #1e3a8a;">Liste des cours</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($role === 'professeur' && $newCoursCount > 0)
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                Vous avez {{ $newCoursCount }} nouveau(x) cours !
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($role === 'admin' || $role === 'gestionnaire')
            <div class="text-end mb-3">
                <a href="{{ route($role . '.cours.create') }}" class="btn btn-primary" style="background-color: #3498db; border: none;">
                    <i class="bi bi-plus-circle me-1"></i> Ajouter un cours
                </a>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead style="background-color: #eef2ff; color: #1e3a8a;">
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Heure début</th>
                            <th>Heure fin</th>
                            <th>Salle</th>
                            <th>Professeur</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($cours as $cour)
                            <tr>
                                <td>
                                    {{ $cour->nom }}
                                    @if ($role === 'professeur' && !$cour->notified_at && $cour->created_at > (auth()->user()->last_login_at ?? '1970-01-01'))
                                        <span class="badge bg-warning text-dark ms-2 blink">
                                            <i class="bi bi-bell"></i> Nouveau
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $cour->description ?? 'N/A' }}</td>
                                <td>{{ $cour->heure_debut->format('d/m/Y H:i') }}</td>
                                <td>{{ $cour->heure_fin->format('d/m/Y H:i') }}</td>
                                <td>{{ $cour->salle->libelle ?? 'N/A' }}</td>
                                <td>{{ $cour->professeur->prenom ?? 'N/A' }} {{ $cour->professeur->nom ?? '' }}</td>
                                <td>
                                    <a href="{{ route($role . '.cours.show', $cour->id) }}" class="btn btn-info btn-sm" style="background-color: #3b82f6; border: none;">
                                        <i class="bi bi-eye"></i> Voir
                                    </a>
                                    @if ($role === 'admin' || $role === 'gestionnaire')
                                        <a href="{{ route($role . '.cours.edit', $cour->id) }}" class="btn btn-warning btn-sm" style="background-color: #f59e0b; border: none;">
                                            <i class="bi bi-pencil"></i> Modifier
                                        </a>
                                        <form action="{{ route($role . '.cours.destroy', $cour->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" style="background-color: #ef4444; border: none;" onclick="return confirm('Voulez-vous vraiment supprimer ce cours ?');">
                                                <i class="bi bi-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Aucun cours disponible.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .blink {
            animation: blinker 1s linear infinite;
        }
        @keyframes blinker {
            50% { opacity: 0; }
        }
    </style>
@endsection
