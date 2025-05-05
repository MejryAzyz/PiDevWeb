# Med Travel

## 🩺🌍 Application de Gestion de Tourisme Médical

---

## 📌 Description du Projet

**Med Travel** est une plateforme web de tourisme médical permettant aux patients étrangers de réserver des services médicaux et hébergements, tout en facilitant la gestion administrative pour les cliniques partenaires.

### Objectifs :
- Mettre en relation patients internationaux et établissements médicaux.
- Offrir une expérience utilisateur fluide pour la recherche, la réservation, le paiement et le suivi des prestations médicales.
- Permettre aux administrateurs et agents de gérer les demandes, les hébergements, les comptes utilisateurs, etc.

### Problème Résolu :
La complexité de l'organisation d’un séjour médical à l’étranger est souvent un frein. Ce projet centralise les démarches et assure un suivi personnalisé.

### Fonctionnalités Principales :
- Authentification sécurisée avec rôles (admin, Responsable Clinique, Utilisateur, Accompagnateur.)
- Formulaire d’inscription avancé (multi-étapes avec vérification d’email)
- Gestion des utilisateurs, réservations et hébergements, paiments, cliniques, accompagnateurs.
- Intégration d'API externes (email, géolocalisation, etc.)
- Tableau de bord pour les responsables et les admins

---

## 🗂️ Table des Matières

- [Installation](#installation)
- [Utilisation](#utilisation)
- [Contribution](#contribution)
- [Licence](#licence)

---

## ⚙️ Installation

1. Clonez le repository :
```bash
git clone https://github.com/MejryAzyz/PiDevWeb
cd PiDevWeb
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony server:start

```
---

## 🛠️ Utilisation : Instructions pour configurer PHP (Symfony utilise PHP comme base)

Pour faire fonctionner **Med Travel**, vous devez installer **PHP**, qui est la base de Symfony. Voici les étapes pour installer et vérifier PHP sur votre machine.

### **Installation de PHP**

Pour utiliser ce projet, vous devez installer **PHP**. Voici les étapes :

1. **Téléchargez PHP à partir du site officiel** :  
   Rendez-vous sur [PHP - Téléchargement](https://www.php.net/downloads.php) pour télécharger la version recommandée (Symfony 6.4 nécessite **PHP 8.1 ou supérieur**).

2. **Installez PHP en suivant les instructions spécifiques à votre système d’exploitation** :  
   - Pour **Windows**, vous pouvez utiliser  ([XAMPP](https://www.apachefriends.org/fr/index.html)) ou  ([WampServer](http://www.wampserver.com/)).  

   - Pour **macOS**, vous pouvez utiliser  ([Homebrew](https://brew.sh/)). Puis exécutez cette commande dans le terminal :
    ```bash
     brew install php
    ```
    - Pour **Linux**, vous pouvez vous pouvez installer PHP via le gestionnaire de paquets. Par exemple, sur Ubuntu :
    ```bash
    sudo apt update
    sudo apt install php
    ```
3. **Vérifiez l’installation de PHP en exécutant la commande suivante dans votre terminal** :  
    ```bash
     php -v
    ```
 ---
   
## 🤝 Contribuer

Nous remercions tous ceux qui ont contribué à **Med Travel** !

### **Nos contributeurs**

Les personnes suivantes ont contribué à ce projet en ajoutant des fonctionnalités, en corrigeant des bugs ou en améliorant la documentation :

- **[Mejri Mouhamed Aziz](https://github.com/MejryAzyz)** : Gestion utilisateur.
- **[Bday Dorra](https://github.com/dorra388)** : Gestion clinique.
- **[Msehli Elyes](https://github.com/ElyesMsehli)** : Gestion hébergement.
- **[Abid Beya](https://github.com/beyaabid123456789)** : Gestion réservation.
- **[Mhamdi Dhia](https://github.com/dhiamhamdi)** : Gestion planning.
- **[Ajroudi Mariem](https://github.com/majroudi94)** : Gestion accompagnateur.

Si vous souhaitez contribuer, suivez les étapes ci-dessous pour faire un **fork**, créer une nouvelle branche et soumettre une **pull request**.

### **Comment contribuer ?**

1. **Forkez le projet** :  
   Allez sur la page GitHub du projet et cliquez sur le bouton **Fork** dans le coin supérieur droit pour créer une copie du projet dans votre propre compte GitHub.

2. **Clonez votre fork** :  
   Clonez le fork sur votre machine locale :  
   ```bash
   git clone https://github.com/MejryAzyz/PiDevWeb.git
   cd PiDevWeb
   ```
---

## 📄 Licence

Ce projet est distribué sous la licence **MIT**. Cela signifie que vous êtes libre d'utiliser, copier, modifier, fusionner, publier, distribuer, sous-licencier et/ou vendre des copies du logiciel, sous réserve de respecter les conditions de la licence.

Pour plus d’informations, veuillez consulter le fichier [LICENSE](./LICENSE).

### **Détails sur la licence MIT**

La licence MIT est une licence de logiciel libre permissive. Elle permet une grande flexibilité dans l’utilisation du code, tant pour des projets personnels que commerciaux, tout en déclinant toute responsabilité de l’auteur.

Voici un résumé :

- ✅ Utilisation libre à des fins commerciales et privées
- ✅ Modification et distribution autorisées
- ❌ Aucun support ou garantie de la part des auteurs



