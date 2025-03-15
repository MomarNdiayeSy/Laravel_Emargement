<!DOCTYPE html>
<html>
<head>
    <title>Confirmation de votre émargement</title>
</head>
<body>
<p>Bonjour {{ $emargement->professeur->prenom }} {{ $emargement->professeur->nom }},</p>
<p>Votre émargement pour le cours "<strong>{{ $emargement->cours->nom }}</strong>" a été validé par l’administration :</p>
<ul>
    <li><strong>Statut :</strong> {{ $emargement->statut }}</li>
    <li><strong>Date :</strong> {{ $emargement->date->format('d/m/Y H:i') }}</li>
    <li><strong>Salle :</strong> {{ $emargement->cours->salle->libelle ?? 'N/A' }}</li>
</ul>
<p>Merci de votre collaboration.</p>
<p>Cordialement,<br>L’équipe de gestion</p>
</body>
</html>
