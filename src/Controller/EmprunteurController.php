<?php

namespace App\Controller;

use App\Entity\Emprunteur;
use App\Form\EmprunteurType;
use App\Repository\EmprunteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/emprunteur')]
class EmprunteurController extends AbstractController
{
    #[Route('/', name: 'app_emprunteur_index', methods: ['GET'])]
    public function index(EmprunteurRepository $emprunteurRepository): Response
    {
        $emprunteurs = $emprunteurRepository->findAll();

        if (!$this->isGranted('ROLE_ADMIN')) {
            $user = $this->getUser();
            $emprunteur = $user->getEmprunteur();
        }

        return $this->render('emprunteur/index.html.twig', [
            'emprunteurs' => $emprunteurs,
        ]);
    }

    #[Route('/{id}', name: 'app_emprunteur_show', methods: ['GET'])]
    public function show(Emprunteur $emprunteur): Response
    {
        return $this->render('emprunteur/show.html.twig', [
            'emprunteur' => $emprunteur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_emprunteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Emprunteur $emprunteur, EmprunteurRepository $emprunteurRepository): Response
    {
        // sauf si l'utilisateur est un admin,
        // on compare son emprunteur et l'emprunteur qu'il veut modifier
        // s'ils ne coincident pas, le user n'a pas accès à cette page
        if (!$this->isGranted('ROLE_ADMIN')) {
            $user = $this->getUser();
            $userEmprunteur = $user->getEmprunteur();

            if ($userEmprunteur->getId() != $emprunteur->getId()) {
                throw new AccessDeniedException();
            }
        }

        $form = $this->createForm(EmprunteurType::class, $emprunteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emprunteurRepository->save($emprunteur, true);

            return $this->redirectToRoute('app_emprunteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('emprunteur/edit.html.twig', [
            'emprunteur' => $emprunteur,
            'form' => $form,
        ]);
    }
}
