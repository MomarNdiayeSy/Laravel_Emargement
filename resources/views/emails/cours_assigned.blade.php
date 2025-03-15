<!DOCTYPE html>
<html>
<head>
    <title>Notification de Cours</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #2c3e50;
            font-size: 24px;
            text-align: center;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
            padding: 5px;
            background-color: #fff;
            border-radius: 3px;
        }
        strong {
            color: #2980b9;
        }
        .signature {
            margin-top: 20px;
            font-style: italic;
            color: #7f8c8d;
        }
        .highlight {
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Notification de Cours</h1>

    @if ($action === 'created')
        <p>Bonjour {{ $cours->professeur->prenom }},</p>
        <p>Nous sommes ravis de vous informer qu’un <span class="highlight">nouveau cours</span> vous a été assigné ! Voici les détails :</p>
    @elseif ($action === 'updated')
        <p>Bonjour {{ $cours->professeur->prenom }},</p>
        <p>Juste un petit mot pour vous signaler qu’un cours auquel vous êtes assigné a été <span class="highlight">mis à jour</span>. Voici les nouvelles informations :</p>
    @elseif ($action === 'deleted')
        <p>Bonjour {{ $cours->professeur->prenom }},</p>
        <p>Nous souhaitions vous prévenir que le cours "{{ $cours->nom }}" a été <span class="highlight">annulé</span>. Pas d’inquiétude, nous allons vous assigner une nouvelle opportunité très bientôt ! Voici les détails du cours annulé :</p>
    @endif

    <ul>
        <li><strong>Nom :</strong> {{ $cours->nom }}</li>
        <li><strong>Description :</strong> {{ $cours->description ?? 'Aucune description disponible' }}</li>
        <li><strong>Heure de début :</strong> {{ $cours->heure_debut->format('d/m/Y à H:i') }}</li>
        <li><strong>Heure de fin :</strong> {{ $cours->heure_fin->format('d/m/Y à H:i') }}</li>
        <li><strong>Salle :</strong> {{ $cours->salle->libelle ?? 'Non spécifiée' }}</li>
        <li><strong>Professeur :</strong> {{ $cours->professeur->prenom }} {{ $cours->professeur->nom }}</li>
    </ul>

    <p class="signature">
        À très bientôt,<br>
        L’équipe administrative<br>
        <small>Pour toute question, n’hésitez pas à nous contacter !</small>
    </p>
</div>
</body>
</html>
