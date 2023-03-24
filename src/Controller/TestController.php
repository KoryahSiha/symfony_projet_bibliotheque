<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\LivreRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/test')]
class TestController extends AbstractController
{
    #[Route('/user', name: 'app_test_user')]
    public function user(UserRepository $repository): Response
    {
        $users = $repository->findAllUsers();
        dump($users);

        $user1 = $repository->find(1);
        dump($user1);

        $userRoles = $repository->findByUserRoles();
        dump($userRoles);

        exit();
    }

    #[Route('/livre', name: 'app_test')]
    public function livre(LivreRepository $repository): Response
    {
        $livres = $repository->findAllLivres();
        dump($livres);

        $livre1 = $repository->find(1);
        dump($livre1);

        $livres = $repository->findByKeyword('lorem');
        dump($livres);

        exit();
    }

}
