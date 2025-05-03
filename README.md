# Module-Note : Gestion de modules et notes étudiants

Ce projet est une application web en PHP/MySQL permettant :
- la gestion des étudiants et de leurs modules,
- la gestion des notes et observations sur chaque module,
- la différenciation entre un espace administrateur (gestion des étudiants) et un espace étudiant.

Ce README détaille le rôle de chaque dossier/fichier du projet.

---

## Table des matières

- [1. Arborescence du projet](#1-arborescence-du-projet)
- [2. Description des dossiers et fichiers](#2-description-des-dossiers-et-fichiers)
  - [2.1. Racine](#21-racine)
  - [2.2. admin/](#22-admin)
  - [2.3. base_donne/](#23-base_donne)
  - [2.4. css/](#24-css)
  - [2.5. js/](#25-js)
  - [2.6. script-php/](#26-script-php)
  - [2.7. pages/](#27-pages)
  - [2.8. image/ et autres](#28-image-et-autres)
- [3. Fonctionnalités principales](#3-fonctionnalites-principales)
- [4. Installation et utilisation](#4-installation-et-utilisation)
- [5. Sécurité & bonnes pratiques](#5-securite--bonnes-pratiques)

---

## 1. Arborescence du projet

```
/
├── admin/
│   └── admin_student.php
├── base_donne/
│   ├── connexion.php
│   └── smart_notes.sql
├── css/
│   ├── style.css
│   ├── stylelogin.css
│   ├── styleobservation.css
│   └── styleadmin.css
├── image/
├── js/
│   ├── script.js
│   └── script-observation.js
├── pages/
│   ├── login.html
│   ├── register.html
│   └── observation.html
├── script-php/
│   ├── login.php
│   ├── logout.php
│   ├── register.php
│   ├── session_handler.php
│   └── sessioncookies.php
├── index.php
└── README.md
```

---

## 2. Description des dossiers et fichiers

### 2.1. Racine

- **index.php**  
  Page d’accueil principale. Affiche le menu, la connexion, la modale d’ajout de module, la liste dynamique des modules pour l’étudiant connecté.

- **README.md**  
  Ce fichier : documentation globale du projet.

---

### 2.2. `admin/`

- **admin_student.php**  
  Interface d’administration : permet d’ajouter, modifier, supprimer les étudiants. Ne s’affiche que si l’utilisateur est admin.  
  Affiche la liste des étudiants, leur admin créateur, propose édition/suppression.

- **styleadmin.css**  
  Feuille de style dédiée à l’espace admin.

---

### 2.3. `base_donne/`

- **connexion.php**  
  Script de connexion à la base de données MySQL via PDO.

- **smart_notes.sql**  
  Structure SQL complète :  
  - Table `admin` : comptes administrateurs.
  - Table `student` : comptes étudiants (liés à admin).
  - Table `modules` : modules liés à chaque étudiant.
  - Table `notes` : notes/observations liées à chaque module et étudiant.
  - Table `sessions` : stockage des sessions PHP en base.

---

### 2.4. `css/`

- **style.css**  
  Styles globaux de l’application (barre de navigation, boutons, modales, modules…).

- **stylelogin.css**  
  Styles pour la page de connexion.

- **styleobservation.css**  
  Styles pour la page d’observation/note sur un module.

- **styleadmin.css**  
  (Voir plus haut) Styles spécifiques à l’espace admin.

---

### 2.5. `js/`

- **script.js**  
  JS principal pour l’étudiant :  
  - Gestion de la modale d’ajout de module.
  - Affichage dynamique des modules (AJAX à compléter pour persistance).
  - Navigation vers la page d’observation d’un module.

- **script-observation.js**  
  JS pour la gestion des observations/notes sur un module.

---

### 2.6. `script-php/`

- **login.php**  
  Traite la connexion :  
  - Vérifie si l’utilisateur existe (admin ou étudiant),  
  - Gère les cookies de session,  
  - Redirige selon le type d’utilisateur.

- **logout.php**  
  Déconnecte l’utilisateur, détruit la session, supprime les cookies.

- **register.php**  
  Traite l’inscription d’un nouvel étudiant (hash du mot de passe).

- **session_handler.php**  
  Gestion personnalisée des sessions PHP (stockées en base, nettoyage, etc.).

- **sessioncookies.php**  
  Vérifie l’état de connexion (cookie/session), gère la redirection automatique selon le rôle (admin/étudiant).

---

### 2.7. `pages/`

- **login.html**  
  Formulaire de connexion.

- **register.html**  
  Formulaire d’inscription étudiant.

- **observation.html**  
  Page d’observation et de saisie de notes/commentaires sur un module.

---

### 2.8. `image/` et autres

- **image/**  
  Contient les logos, images d’illustration.

- **schema/**  
  (Si présent) : documentation, schémas ou exports du modèle de données.

---

## 3. Fonctionnalités principales

- **Connexion/inscription sécurisée** (admin & étudiant)
- **Gestion des étudiants** (pour l’admin)
- **Ajout/suppression de modules** (pour l’étudiant, à compléter côté serveur)
- **Gestion des notes/observations** (par module)
- **Stockage sécurisé des sessions**
- **Interface responsive et ergonomique**

---

## 4. Installation et utilisation

1. **Cloner le projet**
2. **Importer le fichier `base_donne/smart_notes.sql`** dans votre serveur MySQL.
3. **Configurer les accès BDD** dans `base_donne/connexion.php` si besoin.
4. **Lancer un serveur PHP** (XAMPP, WAMP, etc.), placer le projet dans le répertoire `www` ou `htdocs`.
5. **Accéder à `localhost/projet/index.php`** via votre navigateur.
6. **Créer un admin directement en BDD** (cf. table `admin`).

---

## 5. Sécurité & bonnes pratiques

- Les mots de passe étudiants sont hashés, mais ceux des admins sont à hasher pour plus de sécurité.
- Les sessions sont stockées en base, limitant le vol de session.
- Les cookies sont protégés (`httponly`, `samesite`).
- Valider/sécuriser toutes les entrées utilisateurs côté PHP (SQL injection/XSS).
- Pour la production : passer tous les mots de passe en hash (admin inclus), utiliser HTTPS, vérifier le code JS côté serveur.

---

## Contribution

N’hésitez pas à forker, proposer des PR ou issues !

---
