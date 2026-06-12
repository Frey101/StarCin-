# StarCiné — Application Web de Gestion Électorale (SAÉ 3.01)

> **Projet académique** — BUT2 Informatique (Semestre 3) — IUT de Saint-Dié  
> **Binôme :** Asya ELIK & Alyssia FREY  
> **Enseignants référents :** R3.01 (Développement Web) & R3.04 (Qualité de Développement)

---

## Présentation du Projet

**StarCiné** est une application web dynamique et sécurisée conçue dans le cadre de la **SAÉ 3.01 (Développement d'une application)**. L'objectif principal de ce projet est de remplacer le vote papier traditionnel par un système électoral numérique fiable, transparent et hautement sécurisé.

### Contexte choisi : Le Cinéma Communautaire
Pour ce projet, nous avons choisi le contexte **culturel et cinématographique**. **StarCiné** permet aux membres d'une communauté ou d'un cinéma local de voter pour désigner les films à l'affiche, d'élire le "Film de l'année", ou de départager des propositions de réalisateurs. 

Le mode de scrutin implémenté est le **scrutin majoritaire à un tour** (avec contraintes d'unicité et d'anonymat stricts).

---

## Exigences et Sécurité (Cahier des Charges)

Conformément aux directives strictes du sujet de l'IUT, l'architecture de StarCiné implémente nativement les piliers électoraux suivants :

* **Anonymat absolu et irréversible :** Séparation technique totale en base de données entre l'émargement de l'électeur et son bulletin de vote. Aucune clé étrangère ni journalisation ne permet de relier l'identité d'un utilisateur à son vote.
* **Unicité du scrutin :** Un électeur authentifié ne peut voter qu'une seule fois par session électorale. Dès que le bulletin est soumis, l'action est enregistrée dans la table d'émargement et devient irréversible.
* **Gestion temporelle automatisée :** * *Avant le scrutin :* Accès aux fiches des candidats/films uniquement, vote bloqué.
    * *Pendant le scrutin :* Vote ouvert aux utilisateurs éligibles.
    * *Après la clôture :* Calcul et proclamation automatique des résultats agrégés.
* **Secret partiel absolu :** Aucun résultat partiel ou tendance n'est accessible avant la clôture électorale, y compris pour les administrateurs du site.

---

## 🛠️ Fonctionnalités Implémentées

### Espace Public / Électeurs
* **Authentification sécurisée :** Système de sessions PHP pour la gestion des comptes (Visiteurs, Membres, Administrateurs, Réalisateurs).
* **Consultation dynamique :** Affichage des films/candidats en lice via des fiches générées dynamiquement en PHP.
* **Espace de vote :** Interface épurée et ergonomique permettant de soumettre son choix en un clic.
* **Outils Collaboratifs :** Intégration d'un **forum communautaire** (chat/commentaires publics) permettant aux membres d'échanger sur les films.

### Espace Administration (Dashboard)
* **Opérations CRUD complètes :** Interface dédiée à l'ajout, la modification ou la suppression de films, candidats ou comptes utilisateurs.
* **Contrôle du Scrutin :** Configuration des dates d'ouverture et de fermeture de l'élection.
* **Visualisation des résultats :** Restitution graphique et analytique des résultats agrégés immédiatement après la clôture du scrutin.

---

## Technologies et Architecture

Le projet a été développé en **PHP natif** (sans framework) afin de maîtriser l'architecture fondamentale du web et d'assurer une qualité logicielle maîtrisée à la racine.

* **Back-End :** PHP 8.x (Architecture **MVC** - Modèle-Vue-Contrôleur) respectant les principes de conception **SOLID** (notamment la responsabilité unique SRP).
* **Persistance :** SGBD relationnel **MySQL**, requêtes d'abstraction via **PDO**.
* **Sécurité :** Utilisation systématique de **requêtes préparées** pour éradiquer les injections SQL, chiffrement des mots de passe en base (bcrypt), et filtrage des entrées (XSS).
* **Front-End :** HTML5 sémantique, CSS3 (modules *Flexbox* et *Grid*) assurant un design fluide et adaptatif (**Responsive Design**).

---

## Démarche Qualité & Versioning

Conformément à la ressource **R3.04 (Qualité de développement)**, une attention particulière a été accordée à la structure du projet :
* **Git & Workflow Collaboratif :** Messages de commits sémantiques et explicites matérialisant la répartition équitable du travail au sein du binôme.
* **Jeux d'essais :** Validation systématique des scénarios de vote aux limites du calendrier (avant, pendant, après).
* **Documentation :** Code commenté et architecture modulaire facilitant la maintenabilité de l'application selon la norme ISO 25010.

---

## Installation et Lancement Local

### Prérequis
* Un serveur local (XAMPP, WAMP, MAMP ou Docker) avec **PHP 8.0+** et **MySQL**.

### Étapes
1. **Cloner le dépôt :**
```bash
git clone [https://github.com/Frey101/StarCin-.git](https://github.com/Frey101/StarCin-.git)

Pour vous connecter en tant que admin il suffit d'appuyer sur 
connexion, puis écrire adresse mail: admin@starcin.com Mot de passe: password

Pour accéder en tant qu'utilisateur: user@example.com  et mot de passe: password.

Pour accéder en tant que réalisateur : realisateur@example.com et mot de passe : password
