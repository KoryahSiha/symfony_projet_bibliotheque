# Projet Bibliothèque en Symfony avec framework

Cet exercice a pour but de maîtriser la création d'une BDD qui sera utilisée dans une application web dynamique.

## Prérequis

- Visual Studio Code
- Terminal bash
- Git
- MariaDB
- PHPMyAdmin
- PHP 8.x
- Symfony
- Composer

## Procédure
Dans VSCode, ouvrir un terminal et entrer les commandes ci-dessous.

### Installation de `symfony-cli` (à faire une seule fois par poste)
`symfony-cli` permet de créer un nouveau projet ou de lancer un serveur web de développement.
Avec Debian ou Ubuntu, entrer les commandes suivantes séparémment :

curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash
sudo apt install symfony-cli

### Activation du protocol HTTPS (à faire une seule fois par poste)
Installer des certificats SSL auto-signés pour pouvoir utiliser le protocole HTTPS dans le navigateur.
Avec Debian ou Ubuntu :
- Installation du package `libnss3-tools` :

sudo apt install libnss3-tools

- Installation de certificats auto-signés :

symfony server:ca:install

### Création de la BDD
Utiliser les install scripts pour créer la base de données.
Se rendre dans le dossier des install scripts :
cd ~/install-scripts

Et entrer ensuite la commande :
<!-- Utiliser des underscores si nom composé. -->
./mkdb.sh nom_base_de_donnees

### Création du projet
Dans le dossier où le projet sera créer, entrer la commande :

<!-- Utiliser des tirets (pas des underscores) pour le nom du projet. Peut avoir le même nom que la BDD. -->
symfony new --webapp --version=lts nom-du-projet

### Créer le fichier contenant le script bash `dofilo.sh` dans le dossier `bin/`
#!/bin/bash

<!-- Suppression de la BDD (ATTENTION !) -->
php bin/console doctrine:database:drop --force --if-exists
<!-- Création de la BDD -->
php bin/console doctrine:database:create --no-interaction
<!-- Exécution du fichier de migration -->
php bin/console doctrine:migrations:migrate --no-interaction
<!-- Injection des données test dans la BDD -->
php bin/console doctrine:fixtures:load --no-interaction

### Configuration du paramètre d'environnement d'exécution et des paramètres d'accès à la BDD
Vérifier la version de MariaDB :
mariadb --version

Créer un fichier `.env.local` puis configurer :

APP_ENV=dev
DATABASE_URL="mysql://nom_base_de_donnees:mot-de-passe@127.0.0.1:3306/nom_base_de_donnees?serverVersion=mariadb-10.3.38&charset=utf8mb4"

### Configuration des paramètres de langue
Dans le fichier `config/packages/translation.yaml` :

default_locale: fr

### Installation de packages supplémentaires
<!-- Ce package fournit des outils pour générer des données de test -->
doctrine/fixtures-bundle :
composer require orm-fixtures --dev

<!-- Faker est une bibliothèque qui permet de générer de fausses données aléatoires et réalistes pour la BDD -->
fakerphp/faker :
composer require fakerphp/faker --dev

<!-- Permet de convertir une chaîne de caractères en une version simplifiée et optimisée pour être utilisée dans une URL.
Rend les URL plus lisibles et améliore le référencement. -->
javiereguiluz/easyslugger :
composer require javiereguiluz/easyslugger --dev

<!-- Permet de paginer les résultats d'une requête en les séparant en plusieurs pages.
Pratique pour les requêtes volumineuses. -->
knplabs/knp-paginator-bundle :
composer require knplabs/knp-paginator-bundle

## Structure de BDD
### Création de l'entité `User`
php bin/console make:user
<!-- Les noms des entités prennent une majuscule (e.g. GentleKangaroo) -->

### Création des attributs
php bin/console make:entity
<!-- Les noms des propriétés ne prennent pas de majuscule -->

### Création du fichier de migration (à faire à chaque nouvelle entrée dans la BDD)
<!-- php bin/console doctrine:migrations:diff -->
php bin/console do:mi:di

### Exécution du fichier de migration (à faire à chaque création de fichier de migration)
<!-- php bin/console doctrine:migrations:migrate -->
php bin/console do:mi:mi

### Vérification de l'accès à la BDD
<!-- php bin/console doctrine:schema:validate -->
php bin/console do:sc:va

### Création de fixtures de test
<!-- Créé un fichier TestFixtures.php pour créer des données de test -->
php bin/console make:fixtures

Puis initialisation de doctrine et de faker dans le fichier de fixtures de test.

### Création des données de test
Créer les entités avec la commande :
php bin/console make:entity

### Création des relations entre les entités
Indiquer le nom de l'entité possédante une fois la commande suivante validée :
php bin/console make:entity

Puis y ajouter un champ portant le nom de l'entité inverse.

### Création des données statiques
Créer une fonction, puis entrer manuellement les données pour chaque table.

Ex :
public function loadAuteurs(): void
{
    $datas = [
        [
            'nom' => null,
            'prenom' => null
        ],
        [
            'nom' => 'Cartier',
            'prenom' => 'Hugues'
        ],
        [
            'nom' => 'Lambert',
            'prenom' => 'Armand'
        ],
        [
            'nom' => 'Moitessier',
            'prenom' => 'Thomas'
        ],
        ];

    foreach ($datas as $data) {
        $auteur = new Auteur();

        $auteur->setNom($data['nom']);
        $auteur->setPrenom($data['prenom']);

        $this->manager->persist($auteur);
    }

    $this->manager->flush();
}

### Création des données dynamiques
Dans cette même fonction, créer une boucle for en indiquant le nombre souhaité de données totales générées aléatoirement pour chaque table.

Ex : 
{
    # ...

    for ($i = 0; $i < 500; $i++) {
        $auteur = new Auteur();

        $auteur->setNom($this->faker->lastname());
        $auteur->setPrenom($this->faker->firstname());

        $this->manager->persist($auteur);
    }

    $this->manager->flush();
}

### Récupérer le repository d'une classe
Pour lier une entité à une autre, il faut récupérer son repository.

Ex :
public function loadEmprunts(): void
{
    $repository = $this->manager->getRepository(Emprunteur::class);
    $Emprunteurs = $repository->findAll();

    $datas = [
        [
            'date_emprunt' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-01 10:00:00'),
            'date_retour' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-03-01 10:00:00'),
            'emprunteur' => $Emprunteurs[0],
            # ...
        ],
        # ...
    ]
    foreach ($datas as $data) {
        $emprunt = new Emprunt();

        $emprunt->setDateEmprunt($data['date_emprunt']);
        $emprunt->setDateRetour($data['date_retour']);
        $emprunt->setEmprunteur($data['emprunteur']);
        # ...

        $this->manager->persist($emprunt);
    }

    $this->manager->flush();
}

### Charger les fixtures dans la BDD
Pour charger les données de test :
<!-- php bin/console doctrine:fixtures:load -->
php bin/console do:fi:lo
[yes]

Pour effacer et re-injecter les données :
php bin/console do:fi:lo
[yes]

Pour purger la BDD et re-injecter les données en repartant de l'id 1 :
bin/dofilo.sh
