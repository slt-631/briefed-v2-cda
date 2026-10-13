# Briefed

Briefed est une application web qui permet de créer des présentations visuelles soignées en quelques clics et de les exporter en PNG.

Ce projet a été réalisé dans le cadre du titre professionnel **Développeur web et web mobile** (projet de formation).

![Éditeur Briefed](docs/screenshot-editeur.png)

## Fonctionnalités

- **Compte utilisateur** : inscription, connexion et déconnexion.
- **Dashboard des présentations** : liste, création, modification et suppression de ses présentations.
- **Éditeur canvas** :
    - choix du format ;
    - fond uni, dégradé ou image ;
    - position et échelle de l'image importée ;
    - bordure et coins arrondis ;
    - ombre.
- **Export PNG** réalisé côté navigateur grâce à l'API Canvas.

## Stack technique

| Élément                                                  | Version        |
| -------------------------------------------------------- | -------------- |
| PHP                                                      | >= 8.2         |
| Symfony (framework, security, form, twig, asset-mapper…) | 7.4.\*         |
| Doctrine ORM                                             | ^3.6           |
| Doctrine Bundle                                          | ^3.2           |
| Doctrine Migrations Bundle                               | ^4.0           |
| Symfony Stimulus Bundle                                  | ^3.2           |
| Symfony UX Turbo                                         | ^3.2           |
| Stimulus (importmap)                                     | 3.2.2          |
| Turbo (importmap)                                        | 8.0.23         |
| MariaDB (Docker)                                         | 10.11          |
| phpMyAdmin (Docker)                                      | dernière image |

Le front utilise Twig, Asset Mapper et Stimulus, sans bundler JavaScript.

## Installation

### Prérequis

- PHP 8.2 ou supérieur
- Composer
- Docker et Docker Compose
- Symfony CLI (pour `symfony serve`)

### Étapes

```bash
# 1. Cloner le dépôt
git clone https://github.com/slt-631/briefed-v2-cda.git
cd briefed-v2-cda

# 2. Installer les dépendances PHP
composer install

# 3. Lancer les services Docker (MariaDB, phpMyAdmin…)
docker compose up -d

# 4. Créer la configuration locale à partir du modèle
cp .env.example .env.local
# puis renseigner dans .env.local vos propres valeurs (APP_SECRET, DATABASE_URL)

# 5. Créer le schéma de la base de données
php bin/console doctrine:migrations:migrate

# 6. Démarrer le serveur
symfony serve
```

L'application est ensuite accessible à l'adresse indiquée par `symfony serve` (généralement https://127.0.0.1:8000).

### Ports utilisés

| Service    | Port |
| ---------- | ---- |
| MariaDB    | 3308 |
| phpMyAdmin | 8080 |

## Structure du projet

```
src/
├── Controller/   Contrôleurs (accueil, inscription, connexion, présentations)
├── Entity/       Entités Doctrine (User, Presentation, Background)
├── Form/         Formulaires Symfony
├── Repository/   Repositories Doctrine
└── Service/      Services métier (ex. FileUploader)
assets/           JavaScript (Stimulus), styles CSS, point d'entrée app.js
templates/        Vues Twig
migrations/       Migrations Doctrine
config/           Configuration Symfony
public/           Point d'entrée web et images
```

## Auteur

Projet réalisé par **slt-631** dans le cadre de la formation Développeur web et web mobile. Il s'agit d'un projet de formation, non destiné à un usage commercial.
