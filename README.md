# EZvents 🎮

EZvents est une plateforme moderne de création, de gestion et d'inscription à des événements (particulièrement axée sur le domaine de l'E-sport). Le projet est propulsé par **Symfony 8**, **Doctrine ORM**, **AssetMapper**, **PostgreSQL** et **Mailpit**.

---

## 📋 Prérequis

Pour installer et faire fonctionner ce projet en local, vous devez disposer des outils suivants :

- **PHP 8.4** ou version supérieure
  - Extensions PHP requises : `ctype`, `iconv`, `pdo_pgsql`
- **Composer** (gestionnaire de dépendances PHP)
- **Docker** & **Docker Compose** (pour faire tourner PostgreSQL et Mailpit)
- **Symfony CLI** *(Optionnel mais recommandé pour lancer le serveur local)*

---

## 🛠️ Installation et Configuration

Toutes les commandes ci-dessous doivent être exécutées depuis le dossier racine du projet Symfony (`EZvents/`):

### 1. Se positionner dans le dossier de l'application Symfony
```bash
cd EZvents
```

### 2. Installer les dépendances PHP
```bash
composer install
```

### 3. Lancer les services Docker (PostgreSQL & Mailpit)
Assurez-vous que votre démon Docker est démarré, puis lancez les conteneurs en arrière-plan :
```bash
docker compose up -d
```
Cela démarrera :
- Une base de données **PostgreSQL** (port `5432`)
- L'outil de test d'emails **Mailpit** (port SMTP `1025`, interface web de lecture des emails sur `http://localhost:8025`)

### 4. Configuration des variables d'environnement
Un fichier `.env` par défaut est déjà configuré pour pointer sur la base de données PostgreSQL de Docker :
```env
DATABASE_URL="postgresql://postgres:app@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
```
Si vous devez personnaliser ces accès, vous pouvez créer un fichier `.env.local` et y surcharger les variables nécessaires.

### 5. Créer la base de données et appliquer les migrations
Générez la structure des tables dans PostgreSQL à l'aide de Doctrine :
```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

---

## 🚀 Démarrer l'application

### Lancer le serveur web local

* **Option A (Recommandée - Symfony CLI) :**
  ```bash
  symfony server:start -d
  ```
  L'application sera alors accessible à l'adresse suivante : **`http://127.0.0.1:8000`**

* **Option B (PHP intégré) :**
  ```bash
  php -S 127.0.0.1:8000 -t public
  ```

### Accéder aux services associés
- **Application Web** : `http://localhost:8000`
- **Mailpit (Boîte mail de test)** : `http://localhost:8025` pour intercepter et visualiser tous les emails envoyés par l'application locale.

---

## 🧪 Exécuter les tests

Le projet intègre une suite de tests unitaires et fonctionnels propulsée par **PHPUnit 13**.

### 1. Mettre à jour la base de données de test
Avant de lancer les tests pour la première fois ou après chaque modification du schéma des entités :
```bash
php bin/console doctrine:schema:update --force --env=test
```

### 2. Lancer la suite de tests
```bash
vendor/bin/phpunit
```

---

## 📁 Structure du projet

Voici un aperçu de la structure principale du code source :

- `src/` :
  - `Controller/` : Les contrôleurs de l'application (Inscription, Profil, Recherche, Gestion des événements...).
  - `Entity/` : Les modèles de données reliés à la base de données via Doctrine (`User`, `Event`).
  - `Form/` : Les classes de définition des formulaires (`RegistrationFormType`, `EventFormType`, `UserFormType`).
  - `Repository/` : Les classes d'accès et de requêtage personnalisé de la base de données.
- `templates/` : Les templates Twig pour le rendu HTML (Accueil, Profil, Événements, Erreurs...).
- `assets/` : Les fichiers styles (CSS), scripts (JS) et images gérés via AssetMapper.
- `tests/` : Les classes de tests PHPUnit organisées par type.
