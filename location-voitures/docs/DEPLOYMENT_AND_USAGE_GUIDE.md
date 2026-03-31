# AutoLoc - Guide rapide (images, reservation, admin)

## 1) Strategie images (professionnelle et stable)
- Upload voitures dans `public/images/images_voiture`
- Nom de fichier sauvegarde en base (ex: `car_65f3...jpg`)
- Affichage public via `asset('images/images_voiture/...')`
- Compatibilite legacy: anciennes images `storage/app/public` restent affichables
- Si image absente/fichier supprime: placeholder local `public/images/placeholders/car-default.svg`

## 2) Documents sensibles (CIN/permis)
- Stockage prive: `storage/app/private/identity/...`
- Jamais exposes en URL publique
- Telechargement possible uniquement via route admin protegee

## 3) Flux reservation
1. Client choisit voiture + dates + ville active
2. Calcul: `prix_location + frais_deplacement`
3. Verification disponibilite (dates chevauchantes)
4. Enregistrement reservation avec reference contrat
5. Generation contrat PDF
6. Envoi email SMTP (si echec, reservation conservee + log)
7. Telechargement PDF depuis confirmation / mes reservations

## 4) Changement de machine / deploiement
1. Restaurer la base MySQL
2. Copier les fichiers de `public/images/images_voiture` et `storage/app/private` (backup)
3. Executer:
   - `php artisan migrate --seed`
   - `php artisan optimize:clear`

## 5) GitHub et base de donnees
- Push GitHub = code uniquement (pas les donnees MySQL en cours).
- Les fichiers `*.sql` sont ignores volontairement.
- Pour reconstruire des donnees identiques sur une autre machine:
  - `php artisan migrate:fresh --seed`
- Les vehicules peuvent aussi etre synchronises via `database/seed-data/cars.json` (mis a jour automatiquement apres create/update/delete depuis l'admin).
- Les images associees aux vehicules sont synchronisees dans `database/seed-data/car-images/` puis restaurees automatiquement vers `public/images/images_voiture`.
