# 🌵 Mathéopolis

<p align="center">
  <img src="public/Images/favicon.png" alt="Logo Mathéopolis" width="120">
  <br><br>
  <strong>Plateforme web éducative et ludique autour de l'univers des ouvrages Mathéopolis.</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Architecture-MVC%20from%20scratch-orange?style=flat-square" alt="MVC">
  <img src="https://img.shields.io/badge/Statut-En%20d%C3%A9veloppement-blue?style=flat-square" alt="Statut">
</p>

---

## 📖 Sommaire
- [À propos du projet](#-à-propos-du-projet)
- [Fonctionnalités clés](#-fonctionnalités-clés)
- [Architecture & Technologies](#-architecture--technologies)
- [Installation & Démarrage](#-installation--démarrage)
- [Conception & Maquettes](#-conception--maquettes)
- [Équipe de développement](#-équipe-de-développement)

---

## 🎯 À propos du projet

Ce projet est développé dans le cadre de la **Situation d'Apprentissage et d'Évaluation (SAÉ) de 2ᵉ année de BUT Informatique**.

L'objectif est de proposer une application web interactive qui rend l'apprentissage des mathématiques attractif et immersif pour les élèves, en s'appuyant sur l'univers et la pédagogie des livres **Mathéopolis**.

---

## ✨ Fonctionnalités clés

### 🧑‍🎓 Espace Élève & Visiteur
- **Univers narratif** : immersion dans l'histoire de Mathéopolis.
- **Bibliothèque** : consultation et découverte des ouvrages de la saga.
- **Activités interactives** : défis mathématiques et résolution d'opérations arithmétiques.
- **Profil élève** : consultation du code de classe attribué par l'enseignant.

### 👨‍🏫 Espace Enseignant
- **Tableau de bord classe** : suivi et gestion de la classe.
- **Génération de code de classe** : création sécurisée et dynamique d'un identifiant de classe.
- **Création de comptes élèves** : ajout individuel avec identifiant et mot de passe.
- **Importation CSV** : ajout massif et automatisé d'une liste d'élèves via un fichier `.csv`.

### 🔐 Sécurité & Authentification
- Système d'inscription et de connexion différencié selon les rôles (`student`, `teacher`).
- Réinitialisation de mot de passe par jeton sécurisé (*token*).
- Gestion de session et possibilité de suppression de compte.

---

## 🏗️ Architecture & Technologies

Le projet repose sur une **architecture MVC (Modèle-Vue-Contrôleur) développée sur-mesure en PHP** :
- **Routeur dynamique** (`core/Router.php`, `core/Request.php`, `core/Response.php`) : gestion centralisée des requêtes HTTP et dispatching vers les contrôleurs.
- **Base de données** (`core/DatabaseConnection.php`) : implémentation du patron de conception **Singleton** et requêtes sécurisées avec PDO.
- **Composants d'interface modulaires** (`src/views/components/`) : bibliothèque de composants HTML réutilisables (Button, Form, Input, Alert, Typewriter, etc.).
- **Sécurisation de l'environnement** : gestion des variables sensibles via `.env` et masquage des fichiers sensibles via `.htaccess`.

---

## 🚀 Installation & Démarrage

### Prérequis
- **PHP 8.1** ou supérieur (avec extension `pdo_mysql`)
- **MySQL** ou **MariaDB**
- Un navigateur web moderne

### 1. Cloner le dépôt
```sh
git clone https://github.com/EwanFRANCOIS/Matheopolis.git
cd Matheopolis
```

### 2. Configuration de l'environnement
Copiez le fichier d'exemple pour créer votre fichier `.env` :
```sh
cp .env.example .env
```

Renseignez ensuite vos identifiants de base de données dans `.env` :
```env
DB_HOST=127.0.0.1
DB_NAME=math3opolis
DB_USER=votre_utilisateur
DB_PASS=votre_mot_de_passe
```

### 3. Lancer l'application

#### Option A : Serveur de développement PHP (recommandé)
Depuis la racine du projet, lancez le serveur intégré en pointant sur le dossier `public/` :
```sh
php -S localhost:8000 -t public
```

#### Option B : Serveur Apache (Laragon, WAMP, XAMPP...)
Le fichier `.htaccess` à la racine redirige automatiquement le trafic vers le dossier `public/`. Placez simplement le dossier du projet dans la racine web (`www` ou `htdocs`).

Accédez ensuite à l'application dans votre navigateur : [http://localhost:8000](http://localhost:8000)

---

## 🎨 Conception & Maquettes
- 🖍️ [Consulter la maquette Figma](https://www.figma.com/design/n6IRV5iQ6clbGnAHbRVYyb/Matheopolis?node-id=0-1&p=f&t=Uf5wDiDgbD4kr0Cz-0)

---

## 💻 Équipe de développement

Projet réalisé par :
- **Ewan François** [![GitHub](https://img.shields.io/badge/GitHub-181717?style=flat-square&logo=github&logoColor=white)](https://github.com/EwanFRANCOIS)
- **Aurèle Jambert** [![GitHub](https://img.shields.io/badge/GitHub-181717?style=flat-square&logo=github&logoColor=white)](https://github.com/Aurele879)
- **Lezina Khadissova** [![GitHub](https://img.shields.io/badge/GitHub-181717?style=flat-square&logo=github&logoColor=white)](https://github.com/KhadissovaLezina)
- **Nina Matic Charbit** [![GitHub](https://img.shields.io/badge/GitHub-181717?style=flat-square&logo=github&logoColor=white)](https://github.com/NinaMATCHA)
- **Nicolas Moyenin** [![GitHub](https://img.shields.io/badge/GitHub-181717?style=flat-square&logo=github&logoColor=white)](https://github.com/NicolasMoyenin)
