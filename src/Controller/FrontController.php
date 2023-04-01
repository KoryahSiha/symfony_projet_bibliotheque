<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(LivreRepository $livreRepository): Response
    {
        $livres = $livreRepository->findAll();

        return $this->render('front/home.html.twig', [
            'livres' => $livres,
        ]);
    }
}
