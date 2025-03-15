<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rapport des émargements - Gestion des Cours</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20mm;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #1e3a8a;
            font-size: 24px;
            margin-bottom: 20px;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #eef2ff;
            color: #1e3a8a;
            font-weight: bold;
            text-transform: uppercase;
        }
        td {
            background-color: #fff;
        }
        tr:nth-child(even) td {
            background-color: #f9fafb;
        }
        .statut-valide {
            color: #15803d;
            font-weight: bold;
        }
        .statut-non-valide {
            color: #b91c1c;
            font-weight: bold;
        }
        .header-info {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
        footer {
            position: fixed;
            bottom: 10mm;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #d1d5db;
            padding-top: 5px;
        }
        @page {
            margin: 20mm;
        }
    </style>
</head>
<body>
<div class="header-info">
    <p>Gestion des Cours - Institut Supérieur Informatique</p>
    <p>Rapport généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>
</div>

<h1>Rapport des émargements</h1>

<table>
    <thead>
    <tr>
        <th>Date</th>
        <th>Professeur</th>
        <th>Cours</th>
        <th>Statut</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($emargements as $emargement)
        <tr>
            <td>{{ $emargement->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $emargement->professeur->prenom }} {{ $emargement->professeur->nom }}</td>
            <td>{{ $emargement->cours->nom }}</td>
            <td class="{{ $emargement->statut === 'validé' ? 'statut-valide' : 'statut-non-valide' }}">
                {{ $emargement->statut }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" style="text-align: center; padding: 20px; color: #666;">
                Aucun émargement trouvé.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<footer>
    <p>© {{ date('Y') }} Institut Supérieur Informatique - Page <span class="pageNumber"></span> / <span class="totalPages"></span></p>
</footer>
</body>
</html>
