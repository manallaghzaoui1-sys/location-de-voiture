<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Session expiree</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f6f8fb; color: #1f2937; margin: 0; }
        .wrap { min-height: 100vh; display: grid; place-items: center; padding: 24px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; max-width: 560px; width: 100%; text-align: center; padding: 28px; box-shadow: 0 10px 30px rgba(15,23,42,.08); }
        h1 { margin: 0 0 8px; font-size: 34px; }
        p { margin: 8px 0 20px; color: #4b5563; }
        a { display: inline-block; background: #1f3a5f; color: #fff; text-decoration: none; padding: 10px 16px; border-radius: 10px; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>419</h1>
        <p>Votre session a expire. Merci de reessayer.</p>
        <a href="{{ route('home') }}">Retour a l accueil</a>
    </div>
</div>
</body>
</html>
