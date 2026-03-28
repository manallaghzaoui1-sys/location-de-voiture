<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle reservation client</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; line-height: 1.6;">
    <h2>Nouvelle reservation recue</h2>
    <p>Une nouvelle reservation client vient d etre enregistree.</p>

    <h3>Reservation</h3>
    <ul>
        <li><strong>Reference:</strong> {{ $reservation->contract_reference }}</li>
        <li><strong>Date debut:</strong> {{ $reservation->date_debut->format('d/m/Y') }}</li>
        <li><strong>Date fin:</strong> {{ $reservation->date_fin->format('d/m/Y') }}</li>
        <li><strong>Statut:</strong> {{ $reservation->statut }}</li>
        <li><strong>Prix location:</strong> {{ number_format($reservation->prix_location, 2) }} DH</li>
        <li><strong>Frais deplacement:</strong> {{ number_format($reservation->frais_deplacement, 2) }} DH</li>
        <li><strong>Total:</strong> {{ number_format($reservation->prix_total, 2) }} DH</li>
        <li><strong>Date creation:</strong> {{ $reservation->created_at?->format('d/m/Y H:i') ?? '-' }}</li>
    </ul>

    <h3>Client</h3>
    <ul>
        <li><strong>Nom:</strong> {{ $reservation->user->name }}</li>
        <li><strong>Email:</strong> {{ $reservation->user->email }}</li>
        <li><strong>Telephone:</strong> {{ $reservation->user->telephone ?? '-' }}</li>
        <li><strong>CIN:</strong> {{ $reservation->user->cin ?? '-' }}</li>
        <li><strong>Permis:</strong> {{ $reservation->user->numero_permis ?? '-' }}</li>
    </ul>

    <h3>Voiture et ville</h3>
    <ul>
        <li><strong>Voiture:</strong> {{ $reservation->car->marque }} {{ $reservation->car->modele }} ({{ $reservation->car->annee }})</li>
        <li><strong>Carburant:</strong> {{ $reservation->car->carburant }}</li>
        <li><strong>Ville:</strong> {{ optional($reservation->city)->name ?? '-' }}</li>
    </ul>

    <p>Ce message est envoye automatiquement par {{ config('app.name') }}.</p>
</body>
</html>
