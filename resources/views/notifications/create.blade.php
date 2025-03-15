@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center" style="font-weight: 600; color: #1e3a8a;">Envoyer une notification</h1>

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
                <form action="{{ route('admin.notifications.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="destinataire_id" class="form-label">Destinataire</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <select name="destinataire_id" id="destinataire_id" class="form-control" required>
                                    <option value="">Sélectionner un destinataire</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ old('destinataire_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->prenom }} {{ $user->nom }} ({{ $user->role }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('destinataire_id')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="message" class="form-label">Message</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-chat-text"></i></span>
                                <textarea name="message" id="message" class="form-control" rows="5" required>{{ old('message') }}</textarea>
                            </div>
                            @error('message')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary me-2" style="background-color: #3498db; border: none;">
                            <i class="bi bi-send"></i> Envoyer
                        </button>
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary" style="background-color: #6b7280; border: none;">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
