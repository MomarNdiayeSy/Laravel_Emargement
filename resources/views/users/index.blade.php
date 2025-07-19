@extends('layouts.app')

@section('content')
    <div class="container">
        @if (auth()->user()->role !== 'admin')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Accès non autorisé. Seuls les administrateurs peuvent gérer les utilisateurs.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @else
            <h1 class="mb-4 text-center" style="font-weight: 600; color: #1e3a8a;">Liste des utilisateurs</h1>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="text-end mb-3">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary" style="background-color: #3498db; border: none;">
                    <i class="bi bi-plus-circle me-1"></i> Ajouter un utilisateur
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead style="background-color: #eef2ff; color: #1e3a8a;">
                            <tr>
{{--                                <th>ID</th>--}}
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($users as $user)
                                <tr>
{{--                                    <td>{{ $user->id }}</td>--}}
                                    <td>{{ $user->nom }}</td>
                                    <td>{{ $user->prenom }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                            <span class="badge" style="background-color: {{ $user->role === 'admin' ? '#3498db' : ($user->role === 'professeur' ? '#10b981' : '#f59e0b') }}; color: #fff;">
                                                {{ $user->role }}
                                            </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm" style="background-color: #f59e0b; border: none;">
                                            <i class="bi bi-pencil"></i> Modifier
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" style="background-color: #ef4444; border: none;" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                                                <i class="bi bi-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Aucun utilisateur disponible.</td>
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
