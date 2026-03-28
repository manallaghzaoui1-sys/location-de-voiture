<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de reservation</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6;">
    <h2>Confirmation de votre reservation</h2>
    <p>Bonjour {{ $reservation->user->name }},</p>
    <p>
        Votre reservation a bien ete enregistree.
        Reference contrat: <strong>{{ $reservation->contract_reference }}</strong>
    </p>

    <h3>Details de la reservation</h3>
    <ul>
        <li><strong>Voiture:</strong> {{ $reservation->car->marque }} {{ $reservation->car->modele }}</li>
        <li><strong>Ville:</strong> {{ optional($reservation->city)->name ?? '-' }}</li>
        <li><strong>Date debut:</strong> {{ $reservation->date_debut->format('d/m/Y') }}</li>
        <li><strong>Date fin:</strong> {{ $reservation->date_fin->format('d/m/Y') }}</li>
        <li><strong>Prix location:</strong> {{ number_format($reservation->prix_location, 2) }} DH</li>
        <li><strong>Frais deplacement:</strong> {{ number_format($reservation->frais_deplacement, 2) }} DH</li>
        <li><strong>Montant total:</strong> {{ number_format($reservation->prix_total, 2) }} DH</li>
        <li><strong>Statut:</strong> {{ $reservation->statut }}</li>
    </ul>

    <h3>Coordonnees client</h3>
    <ul>
        <li><strong>Nom:</strong> {{ $reservation->user->name }}</li>
        <li><strong>Email:</strong> {{ $reservation->user->email }}</li>
        <li><strong>Telephone:</strong> {{ $reservation->user->telephone ?? '-' }}</li>
        <li><strong>CIN:</strong> {{ $reservation->user->cin ?? '-' }}</li>
        <li><strong>Permis:</strong> {{ $reservation->user->numero_permis ?? '-' }}</li>
    </ul>

    <p>Le contrat PDF est joint quand il est disponible.</p>
    <p>Cordialement,<br>{{ config('mail.from.name') }}</p>
</body>
</html>
