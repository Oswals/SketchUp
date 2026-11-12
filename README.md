# Sketch2Convert

Application web qui convertit des fichiers SketchUp (`.skp`) d'une version à une autre, de 2013 à 2026. Elle détecte la version du fichier envoyé, le convertit vers la version choisie et propose le résultat en téléchargement. L'accès est payant, par crédits : un crédit correspond à un fichier converti.

## Fonctionnalités

- **Conversion** : envoi d'un fichier `.skp`, détection de sa version, conversion ascendante ou descendante, téléchargement du résultat.
- **Crédits** : packs de fichiers achetés par Stripe ou PayPal, avec historique des achats et des transactions.
- **Comptes** : inscription, connexion par e-mail ou par Google, mot de passe oublié, profil, export des données personnelles.
- **Administration** : gestion des utilisateurs, des conversions et des transactions, paramètres du site, statistiques avec export CSV.
- **Nettoyage automatique** : les fichiers temporaires et les conversions expirées sont supprimés par des tâches planifiées.

## Technologies

| Partie | Outils |
| --- | --- |
| Serveur | PHP, Laravel 11, MySQL |
| Interface | Blade, Tailwind CSS, Vite |
| Paiement | Stripe (`stripe/stripe-php`), PayPal (`srmklive/paypal`) |
| Connexion Google | Laravel Socialite |
| Conversion | Exécutables Windows construits sur le SDK SketchUp |

## Prérequis

- **Windows** : la conversion repose sur des exécutables `.exe`, l'application ne convertit donc rien sur Linux ou macOS.
- **PHP 8.4.1 ou plus**, avec l'extension `zip` : c'est ce qu'exige le `composer.lock` actuel.
- **Composer** et **Node.js**.
- **MySQL**.
- **Les binaires de conversion**, qui ne sont pas dans le dépôt (voir plus bas).

## Installation

```bash
git clone https://github.com/Oswals/SketchUp.git
cd SketchUp

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Renseignez ensuite `.env` (voir la section Configuration), puis créez la base :

```bash
php artisan migrate --seed
```

Le seeder crée un compte administrateur avec des identifiants par défaut. Modifiez-les dans `database/seeders/AdminSeeder.php` avant de lancer la commande, ou changez le mot de passe juste après.

### Binaires de conversion

Placez ces fichiers dans `storage/app/bin/release/` :

- `detector.exe`
- `converter.exe`
- `SketchUpAPI.dll`
- `SketchUpCommonPreferences.dll`

Le dossier `storage/app` est ignoré par git : ces fichiers sont à copier à la main sur chaque machine.

### Lancement

```bash
php artisan serve
npm run dev
```

Dans un autre terminal, pour les tâches de nettoyage planifiées :

```bash
php artisan schedule:work
```

## Configuration

Les variables à renseigner dans `.env` :

| Variable | Rôle |
| --- | --- |
| `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Connexion MySQL |
| `MAIL_HOST`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` | Envoi des e-mails (bienvenue, mot de passe oublié, contact) |
| `MAIL_TO_ADDRESS` | Adresse qui reçoit les messages du formulaire de contact, aussi affichée sur le site |
| `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET` | Paiement Stripe |
| `PAYPAL_MODE`, `PAYPAL_LIVE_CLIENT_ID`, `PAYPAL_LIVE_CLIENT_SECRET` | Paiement PayPal (`sandbox` ou `live`) |
| `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI` | Connexion avec Google |

Le webhook Stripe est à faire pointer vers `/webhook/stripe`.

Ne commitez jamais de vraies clés : `.env` est ignoré par git, et `.env.example` ne doit contenir que des valeurs vides.

## Commandes utiles

| Commande | Effet |
| --- | --- |
| `php artisan conversions:cleanup` | Supprime les conversions expirées |
| `php artisan conversions:cleanup-pending` | Supprime les conversions en attente abandonnées |
| `php artisan conversions:cleanup-orphans` | Supprime les fichiers sans conversion associée |
| `php artisan temp:clean-uploads` | Vide les envois temporaires |
| `php artisan coins:check-purchase {purchase_id}` | Vérifie l'état d'un achat |

Les quatre premières sont planifiées dans `routes/console.php`.

## Organisation du code

| Dossier | Contenu |
| --- | --- |
| `app/Services/SketchUpService.php` | Appel des exécutables de détection et de conversion |
| `app/Http/Controllers` | Conversion, crédits, paiements, authentification, profil |
| `app/Http/Controllers/Admin` | Back-office |
| `app/Console/Commands` | Tâches de nettoyage |
| `resources/views` | Vues Blade (site, authentification, administration, e-mails) |
| `database/migrations`, `database/seeders` | Schéma et données de départ |

## Limites connues

- La conversion s'exécute pendant la requête HTTP, sans file d'attente : un gros fichier peut dépasser le délai du serveur.
- Les fichiers SketchUp 2021 à 2023 partagent le même format, leur version exacte n'est pas toujours identifiable.
- Le projet n'a pas de tests automatisés.

## Contributeurs

Oswals, Heros SESSINOU, Timothee Meyrieux.
