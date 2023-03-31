<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Livre;
use App\Entity\User;
use App\Repository\AuteurRepository;
use App\Repository\EmpruntRepository;
use App\Repository\EmprunteurRepository;
use App\Repository\LivreRepository;
use App\Repository\UserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
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

    #[Route('/livre', name: 'app_test_livre')]
    public function livre(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $auteurRepository = $doctrine->getRepository(Auteur::class);
        $livreRepository = $doctrine->getRepository(Livre::class);
        
        $livres = $livreRepository->findAllLivres();
        dump($livres);
        
        $livre1 = $livreRepository->find(1);
        dump($livre1);
        
        $livres = $livreRepository->findByKeyword('lorem');
        dump($livres);

        $auteur = $auteurRepository->find(2);

        $livre = new Livre();
        $livre->setTitre('Totum autem id externum');
        $livre->setAnneeEdition(2020);
        $livre->setNombrePages(300);
        $livre->setCodeIsbn('9790412882714');
        $livre->setAuteur($auteur);

        dump($livre);

        $em->persist($livre);
        $em->flush();

        $livre2 = $livreRepository->find(2);
        $livre2->setTitre('Aperiendum est igitur');

        $em->flush();
        dump($livre2);

        $livre123 = $livreRepository->find(123);
        
        try {
            $em->remove($livre123);
            $em->flush();
        } catch (Exception $e) {
            // interception d'une exception (objet)
            // la description de l'erreur
            dump($e->getMessage());
            // éventuellement numéro de code de l'erreur
            dump($e->getCode());
            // l'endroit où l'erreur a été détectée, le fichier
            dump($e->getFile());
            // l'endroit où l'erreur a été détectée, la ligne
            dump($e->getLine());
            // renvoie ce tableau sous chaîne de caractères
            dump($e->getTraceAsString());
        }
        dump($livre123);

        exit();
    }

    #[Route('/emprunteur', name: 'app_test_emprunteur')]
    public function emprunteur(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $emprunteurRepository = $doctrine->getRepository(Emprunteur::class);
        $userRepository = $doctrine->getRepository(User::class);

        $emprunteurs = $emprunteurRepository->findAllEmprunteurs();
        dump($emprunteurs);

        $userId = 3;
        $emprunteur = $emprunteurRepository->findEmprunteurByUserId($userId);
        dump($emprunteur);

        $emprunteurs = $emprunteurRepository->findByKeyword('foo');
        dump($emprunteurs);

        exit();
    }

    #[Route('/emprunt', name: 'app_test_emprunt')]
    public function emprunt(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $empruntRepository = $doctrine->getRepository(Emprunt::class);       
        $livreRepository = $doctrine->getRepository(Livre::class);
        $emprunteurRepository = $doctrine->getRepository(Emprunteur::class);
        
        $livre = $livreRepository->find(1);
        $emprunteur = $emprunteurRepository->find(1);

        $emprunt = new Emprunt();
        $emprunt->setDateEmprunt(DateTime::createFromFormat('Y-m-d H:i:s', '2020-12-01 16:00:00'));
        $emprunt->setDateRetour(null);
        $emprunt->setEmprunteur($emprunteur);
        $emprunt->setLivre($livre);
        dump($emprunt);

        $em->persist($emprunt);
        $em->flush();

        $emprunts = $empruntRepository->findAllOrderByEmprunt();
        dump($emprunts);

        $emprunteurId = 2;
        $emprunt = $empruntRepository->findEmpruntByEmprunteur($emprunteurId);
        dump($emprunt);

        $livreId = 3;
        $emprunt = $empruntRepository->findEmpruntByLivre($livreId);
        dump($emprunt);

        $emprunt = $empruntRepository->findAllByDateEmprunt();
        dump($emprunt);

        $emprunt3 = $empruntRepository->find(3);
        $emprunt3->setDateRetour(DateTime::createFromFormat('Y-m-d H:i:s', '2020-05-01 10:00:00'));

        $em->flush();
        dump($emprunt3);


        exit();
    }

}
