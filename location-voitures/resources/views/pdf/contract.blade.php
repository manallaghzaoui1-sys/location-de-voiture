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
        .signatures { margin-top: 36px; width: 100%; }
        .sign-col { width: 48%; display: inline-block; vertical-align: top; }
        .sign-box { margin-top: 48px; border-top: 1px solid #222; padding-top: 4px; font-size: 11px; text-align: center; }
    </style>
</head>
<body>
    <div class="title">Contrat de location d'une voiture</div>
    <div class="subtitle">Reference contrat: {{ $reservation->contract_reference }} - Genere le {{ $generatedAt->format('d/m/Y H:i') }}</div>

    <div class="section-title">Entre les soussignes</div>
    @foreach(($contractLinesBySection['parties'] ?? []) as $line)
        <div class="line">{{ $line['label'] }}: <span class="value">{{ $line['value'] }}</span></div>
    @endforeach

    <div class="section-title">Il a ete convenu ce qui suit</div>
    <div class="clause-title">1.1 - Nature et date d'effet du contrat</div>
    @foreach(($contractLinesBySection['vehicle'] ?? []) as $line)
        <div class="line">{{ $line['label'] }}: <span class="value">{{ $line['value'] }}</span></div>
    @endforeach

    <div class="clause-title">1.2 - Etat du vehicule</div>
    <div class="line">Lors de la remise du vehicule et lors de sa restitution, un proces-verbal de l'etat du vehicule sera etabli entre le locataire et le loueur.</div>
    <div class="line">Le vehicule devra etre restitue dans le meme etat que lors de sa remise.</div>
    <div class="line">Toutes les deteriorations constatees lors de la restitution restent a la charge du locataire.</div>

    <div class="section-title">Conditions financieres</div>
    @foreach(($contractLinesBySection['financial'] ?? []) as $line)
        <div class="line">{{ $line['label'] }}: <span class="value">{{ $line['value'] }}</span></div>
    @endforeach

    <div class="signatures">
        <div class="sign-col"><div class="sign-box">Signature du loueur</div></div>
        <div class="sign-col" style="float:right;"><div class="sign-box">Signature du locataire</div></div>
    </div>
</body>
</html>
