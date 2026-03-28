# AutoLoc - Guide rapide (images, réservation, admin)

## 1) Stratégie images (professionnelle et stable)
- Upload voitures dans `public/images/images_voiture`
- Nom de fichier sauvegardé en base (ex: `car_65f3...jpg`)
- Affichage public via `asset('images/images_voiture/...')`
- Compatibilité legacy: anciennes images `storage/app/public` restent affichables
- Si image absente/fichier supprimé: placeholder local `public/images/placeholders/car-default.svg`

## 2) Documents sensibles (CIN/permis)
- Stockage privé: `storage/app/private/identity/...`
- Jamais exposés en URL publique
- Téléchargement possible uniquement via route admin protégée

## 3) Flux réservation
1. Client choisit voiture + dates + ville active
2. Calcul: `prix_location + frais_deplacement`
3. Vérification disponibilité (dates chevauchantes)
4. Enregistrement réservation avec référence contrat
5. Génération contrat PDF
6. Envoi email SMTP (si échec, réservation conservée + log)
7. Téléchargement PDF depuis confirmation / mes réservations

## 4) Changement de machine / déploiement
1. Restaurer la base MySQL
2. Copier les fichiers de `public/images/images_voiture` et `storage/app/private` (backup)
3. Exécuter:
   - `php artisan migrate --seed`
   - `php artisan optimize:clear`
