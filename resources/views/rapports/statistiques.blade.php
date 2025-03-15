@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center" style="font-weight: 600; color: #1e3a8a;">Statistiques des présences</h1>

        <!-- Formulaire de filtre dans une carte -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ auth()->user()->role === 'admin' ? route('admin.rapports.statistiques') : (auth()->user()->role === 'professeur' ? route('professeur.rapports.statistiques') : route('gestionnaire.rapports.statistiques')) }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="date_debut" class="form-label">Date de début</label>
                        <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ request('date_debut') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_fin" class="form-label">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ request('date_fin') }}">
                    </div>
                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'gestionnaire')
                        <div class="col-md-3">
                            <label for="professeur_id" class="form-label">Professeur</label>
                            <select name="professeur_id" id="professeur_id" class="form-control">
                                <option value="">Tous les professeurs</option>
                                @foreach ($professeurs as $professeur)
                                    <option value="{{ $professeur->id }}" {{ request('professeur_id') == $professeur->id ? 'selected' : '' }}>
                                        {{ $professeur->prenom }} {{ $professeur->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100" style="background-color: #3498db; border: none;">Filtrer</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Graphique dans une carte -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-center" style="color: #1e3a8a; font-weight: 600;">Répartition des émargements</h5>
                <canvas id="presenceChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Bouton de retour -->
        <div class="mt-4 text-center">
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.rapports.index') : (auth()->user()->role === 'professeur' ? route('professeur.rapports.index') : route('gestionnaire.rapports.index')) }}"
               class="btn btn-secondary" style="background-color: #6b7280; border: none;">
                Retour
            </a>
        </div>
    </div>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('presenceChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels), // Ex. ['présent', 'absent']
                datasets: [{
                    label: 'Nombre d’émargements',
                    data: @json($data), // Ex. [10, 5]
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.6)', // Vert pour "présent" ou autre statut positif
                        'rgba(239, 68, 68, 0.6)'   // Rouge pour "absent" ou autre statut négatif
                    ],
                    borderColor: [
                        'rgba(16, 185, 129, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombre d’émargements',
                            color: '#1e3a8a',
                            font: { size: 14 }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Statut',
                            color: '#1e3a8a',
                            font: { size: 14 }
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#1e3a8a',
                            font: { size: 14 }
                        }
                    }
                }
            }
        });
    </script>
@endsection
