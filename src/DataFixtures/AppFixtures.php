<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $faker;
    private $hasher;
    private $manager;

    public static function getGroups(): array
    {
        return ['group1'];
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

        // @infos, les admins possèdent le rôle admin
        $this->loadUsers();
    }

    public function loadUsers(): void
    {
        $datas = [
            [
                'email' => 'admin@example.com',
                'roles' => ['ROLE_ADMIN'],
                'password' => '123',
                'enabled' => true
            ],
        ];

        foreach ($datas as $data) {
            $user = new User();

            $user->setEmail($data['email']);
            $password = $this->hasher->hashPassword($user, $data['password']);
            $user->setPassword($password);
            $user->setRoles($data['roles']);
            $user->setEnabled($data['enabled']);
            
            $this->manager->persist($user);
        };

        $this->manager->flush();
        }
}
