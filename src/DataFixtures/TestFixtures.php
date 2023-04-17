<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Livre;
use App\Entity\User;
use \DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture implements FixtureGroupInterface
{
    private $doctrine;
    private $faker;
    private $hasher;
    private $manager;

    public static function getGroups(): array
    {
        return ['group2'];
    }

    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $hasher)
    {
        $this->doctrine = $doctrine;
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;

    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadUsers();
        $this->loadAuteurs();
        $this->loadLivres();
        $this->loadEmprunteurs();
        $this->loadEmprunts();
    }

    public function loadUsers(): void
    {
        // création des données statiques
        // $datas = [] est un tableau de données contenant des informations pour créer des utilisateurs.
        // chaque élément du tableau est un tableau associatif avec les clés 'email', 'roles', 'password' et 'enabled'
        $datas = [
            [
                'email' => 'foo.foo@example.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
                'enabled' => true
            ],
            [
                'email' => 'bar.bar@example.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
                'enabled' => false
            ],
            [
                'email' => 'baz.baz@example.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
                'enabled' => true
            ]
        ];

        // boucle qui parcourt le tableau de données $datas
        // pour chaque élément du tableau, il exécute le code entre les accolades
        foreach ($datas as $data) {

            // création d'un nouvel objet
            $user = new User();

            // configuration de l'email de l'utilisateur avec la valeur stockée dans le tableau $datas
            $user->setEmail($data['email']);
            // configuration du rôle de l'utilisateur avec la valeur stockée dans le tableau $datas
            $user->setRoles($data['roles']);
            // utilisation d'une fonction de hachage pour convertir le mot de passe stocké dans le tableau $datas en une valeur sécurisée pour stockage en BDD
            $password = $this->hasher->hashPassword($user, $data['password']);
            // configuration du mot de passe de l'utilisateur avec la valeur hachée
            $user->setPassword($password);
            // configuration du compte de l'utilisateur avec la valeur stockée dans le tableau $datas
            $user->setEnabled($data['enabled']);

            // demande d'enregistrement de l'objet (ajoute l'utilisateur créé dans l'EntityManager de Doctrine, qui se chargera de les enregistrer en base de données plus tard)
            $this->manager->persist($user);
        }

        // création des données dynamiques
        // Création de 100 users
        for ($i = 0; $i < 100; $i++) {
            // cré
            $user = new User();

            $user->setEmail($this->faker->email());
            $user->setRoles(['ROLE_USER']);
            $password = $this->hasher->hashPassword($user, '123');
            $user->setPassword($password);
            $user->setEnabled($this->faker->boolean());

            $this->manager->persist($user);
        }

        // exécution des requêtes SQL pour insérer les nouveaux enregistrements dans la BDD
        $this->manager->flush();
    }

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

        for ($i = 0; $i < 500; $i++) {
            $auteur = new Auteur();

            $auteur->setNom($this->faker->lastname());
            $auteur->setPrenom($this->faker->firstname());

            $this->manager->persist($auteur);
        }

        $this->manager->flush();
    }

    public function loadLivres(): void
    {

        $repository = $this->manager->getRepository(Auteur::class);
        $auteurs = $repository->findAll();

        $datas = [
            [
                'titre' => 'Lorem ipsum dolor sit amet',
                'annee_edition' => 2010,
                'nombre_pages' => 100,
                'code_isbn' => 9785786930024,
                'auteur' => $auteurs[0]
            ],
            [
                'titre' => 'Consectetur adipiscing elit',
                'annee_edition' => 2011,
                'nombre_pages' => 150,
                'code_isbn' => 9783817260935,
                'auteur' => $auteurs[1]
            ],
            [
                'titre' => 'Mihi quidem Antiochum',
                'annee_edition' => 2012,
                'nombre_pages' => 200,
                'code_isbn' => 9782020493727,
                'auteur' => $auteurs[2]
            ],
            [
                'titre' => 'Quem audis satis belle',
                'annee_edition' => 2013,
                'nombre_pages' => 250,
                'code_isbn' => 9794059561353,
                'auteur' => $auteurs[3]
            ],
        ];

        foreach ($datas as $data) {
            $livre = new Livre();

            $livre->setTitre($data['titre']);
            $livre->setAnneeEdition($data['annee_edition']);
            $livre->setNombrePages($data['nombre_pages']);
            $livre->setCodeIsbn($data['code_isbn']);
            $livre->setAuteur($data['auteur']);

            $this->manager->persist($livre);
        }

        for ($i = 0; $i < 1000; $i++) {
            $livre = new Livre();

            $livre->setTitre(ucfirst($this->faker->sentence(4, true)));
            $livre->setAnneeEdition($this->faker->year());
            $livre->setNombrePages($this->faker->numberBetween(50, 1000));
            $livre->setCodeIsbn($this->faker->numerify('97###########'));
            $livre->setAuteur($this->faker->randomElement($auteurs));

            $this->manager->persist($livre);
        }

        $this->manager->flush();
    }

    public function loadEmprunteurs(): void
    {
        $repository = $this->manager->getRepository(User::class);
        $users = $repository->findAll();

        $datas = [
            [
                'nom' => 'foo',
                'prenom' => 'foo',
                'tel' => '123456789',
                'user' => $users[1]
            ],
            [
                'nom' => 'bar',
                'prenom' => 'bar',
                'tel' => '123456789',
                'user' => $users[2]
            ],
            [
                'nom' => 'baz',
                'prenom' => 'baz',
                'tel' => '123456789',
                'user' => $users[3]
            ],
        ];

        foreach ($datas as $data) {
            $emprunteur = new Emprunteur();

            $emprunteur->setNom($data['nom']);
            $emprunteur->setPrenom($data['prenom']);
            $emprunteur->setTel($data['tel']);
            $emprunteur->setUser($data['user']);

            $this->manager->persist($emprunteur);
        }

        $this->manager->flush();
    }

    public function loadEmprunts(): void
    {
        $repository = $this->manager->getRepository(Emprunteur::class);
        $emprunteurs = $repository->findAll();

        $repository = $this->manager->getRepository(Livre::class);
        $livres = $repository->findAll();

        $datas = [
            [
                'date_emprunt' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-01 10:00:00'),
                'date_retour' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-03-01 10:00:00'),
                'emprunteur' => $emprunteurs[0],
                'livre' => $livres[0]
            ],
            [
                'date_emprunt' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-03-01 10:00:00'),
                'date_retour' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-04-01 10:00:00'),
                'emprunteur' => $emprunteurs[1],
                'livre' => $livres[1]
            ],
            [
                'date_emprunt' => DateTime::createFromFormat('Y-m-d H:i:s', '2020-04-01 10:00:00'),
                'date_retour' => null,
                'emprunteur' => $emprunteurs[2],
                'livre' => $livres[2]
            ],
        ];

        foreach ($datas as $data) {
            $emprunt = new Emprunt();

            $emprunt->setDateEmprunt($data['date_emprunt']);
            $emprunt->setDateRetour($data['date_retour']);
            $emprunt->setEmprunteur($data['emprunteur']);
            $emprunt->setLivre($data['livre']);

            $this->manager->persist($emprunt);
        }

        $this->manager->flush();
    }
}
