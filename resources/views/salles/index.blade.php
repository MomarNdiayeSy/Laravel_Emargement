@extends('layouts.app')

@section('content')
    <div class="container">
        @if (auth()->user()->role !== 'admin')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Accès non autorisé. Seuls les administrateurs peuvent gérer les salles.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @else
            <h1 class="mb-4 text-center" style="font-weight: 600; color: #1e3a8a;">Liste des salles</h1>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="text-end mb-3">
                <a href="{{ route('admin.salles.create') }}" class="btn btn-primary" style="background-color: #3498db; border: none;">
                    <i class="bi bi-plus-circle me-1"></i> Ajouter une salle
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead style="background-color: #eef2ff; color: #1e3a8a;">
                            <tr>
                                <th>ID</th>
                                <th>Libellé</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($salles as $salle)
                                <tr>
                                    <td>{{ $salle->id }}</td>
                                    <td>{{ $salle->libelle }}</td>
                                    <td>
                                        <a href="{{ route('admin.salles.edit', $salle->id) }}" class="btn btn-warning btn-sm" style="background-color: #f59e0b; border: none;">
                                            <i class="bi bi-pencil"></i> Modifier
                                        </a>
                                        <form action="{{ route('admin.salles.destroy', $salle->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" style="background-color: #ef4444; border: none;" onclick="return confirm('Voulez-vous vraiment supprimer cette salle ?');">
                                                <i class="bi bi-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Aucune salle disponible.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
