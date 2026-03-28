<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.55; color: #111; margin: 36px 44px; }
        .title { text-align: center; font-size: 22px; font-weight: 700; margin-bottom: 4px; text-decoration: underline; }
        .subtitle { text-align: center; font-size: 11px; color: #555; margin-bottom: 20px; }
        .section-title { margin-top: 22px; margin-bottom: 10px; font-size: 14px; font-weight: 700; text-transform: uppercase; }
        .clause-title { margin-top: 16px; margin-bottom: 8px; font-size: 13px; font-weight: 700; }
        .line { margin: 6px 0; }
        .value { display: inline-block; min-width: 260px; border-bottom: 1px dotted #444; padding: 0 2px 1px; font-weight: 600; }
        .small-note { color: #555; font-size: 10px; }
        .signatures { margin-top: 36px; width: 100%; }
        .sign-col { width: 48%; display: inline-block; vertical-align: top; }
        .sign-box { margin-top: 48px; border-top: 1px solid #222; padding-top: 4px; font-size: 11px; text-align: center; }
    </style>
</head>
<body>
    <div class="title">Contrat de location d'une voiture</div>
    <div class="subtitle">Référence contrat: {{ $reservation->contract_reference }} - Généré le {{ $generatedAt->format('d/m/Y H:i') }}</div>

    <div class="section-title">Entre les soussignés</div>
    <div class="line">Le nom du loueur: <span class="value">{{ config('app.name') }}</span></div>
    <div class="line">Le nom du locataire: <span class="value">{{ $reservation->user->name }}</span></div>
    <div class="line">
        CIN: <span class="value">{{ $reservation->user->cin ?? 'Non renseigné' }}</span>
        &nbsp;&nbsp;&nbsp; Numéro permis: <span class="value">{{ $reservation->user->numero_permis ?? 'Non renseigné' }}</span>
    </div>
    <div class="line">
        Téléphone: <span class="value">{{ $reservation->user->telephone ?? 'Non renseigné' }}</span>
        &nbsp;&nbsp;&nbsp; Email: <span class="value">{{ $reservation->user->email }}</span>
    </div>

    <div class="section-title">Il a été convenu ce qui suit</div>
    <div class="clause-title">1.1 - Nature et date d'effet du contrat</div>
    <div class="line">Le loueur met à disposition du locataire un véhicule de marque: <span class="value">{{ $reservation->car->marque }} {{ $reservation->car->modele }}</span></div>
    <div class="line">Carburant: <span class="value">{{ $reservation->car->carburant }}</span> &nbsp;&nbsp;&nbsp; Année: <span class="value">{{ $reservation->car->annee }}</span></div>
    <div class="line">Immatriculation: <span class="value">Non renseignée</span> <span class="small-note">(champ non disponible dans la base actuelle)</span></div>
    <div class="line">À titre onéreux et à compter du: <span class="value">{{ $reservation->date_debut->format('d/m/Y') }}</span> jusqu'au: <span class="value">{{ $reservation->date_fin->format('d/m/Y') }}</span></div>
    <div class="line">Ville de livraison: <span class="value">{{ optional($reservation->city)->name ?? 'Non renseignée' }}</span></div>
    <div class="line">Kilométrage du véhicule: <span class="value">Non renseigné</span> <span class="small-note">(champ non disponible dans la base actuelle)</span></div>

    <div class="clause-title">1.2 - État du véhicule</div>
    <div class="line">Lors de la remise du véhicule et lors de sa restitution, un procès-verbal de l'état du véhicule sera établi entre le locataire et le loueur.</div>
    <div class="line">Le véhicule devra être restitué dans le même état que lors de sa remise.</div>
    <div class="line">Toutes les détériorations constatées lors de la restitution restent à la charge du locataire.</div>

    <div class="section-title">Conditions financières</div>
    <div class="line">Nombre de jours: <span class="value">{{ $reservation->nombre_jours }}</span></div>
    <div class="line">Prix location: <span class="value">{{ number_format($reservation->prix_location, 2) }} DH</span></div>
    <div class="line">Frais de déplacement: <span class="value">{{ number_format($reservation->frais_deplacement, 2) }} DH</span></div>
    <div class="line">Prix total: <span class="value">{{ number_format($reservation->prix_total, 2) }} DH</span></div>

    <div class="signatures">
        <div class="sign-col"><div class="sign-box">Signature du loueur</div></div>
        <div class="sign-col" style="float:right;"><div class="sign-box">Signature du locataire</div></div>
    </div>
</body>
</html>
