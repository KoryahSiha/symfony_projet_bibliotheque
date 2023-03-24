<?php

namespace App\Controller;

use App\Repository\UserRepository;
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
}
