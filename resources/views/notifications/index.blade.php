@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center" style="font-weight: 600; color: #1e3a8a;">Liste des notifications</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="text-end mb-3">
            <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary" style="background-color: #3498db; border: none;">
                <i class="bi bi-plus-circle me-1"></i> Envoyer une notification
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead style="background-color: #eef2ff; color: #1e3a8a;">
                        <tr>
{{--                            <th>ID</th>--}}
                            <th>Message</th>
                            <th>Destinataire</th>
                            <th>Date d’envoi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($notifications as $notification)
                            <tr>
{{--                                <td>{{ $notification->id }}</td>--}}
                                <td>
                                    <span title="{{ $notification->message }}">{{ Str::limit($notification->message, 50) }}</span>
                                </td>
                                <td>{{ $notification->destinataire->prenom }} {{ $notification->destinataire->nom }}</td>
                                <td>{{ $notification->date_envoi->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Aucune notification disponible.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
