<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\FavorisSent;
use App\Form\AdminType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends AbstractController
{
    #[Route('/compte', name: 'app_compte')]
    public function index(Security $security, EntityManagerInterface $em): Response
    {
        //Récupération des infos de l'utilisateur connecté
        $user = $security->getUser();
        $role = $user->getRoles();
        $userId = $user->getId();

        //Récupération des favoris de l'utilisateur connecté
        $favoris = $em->getRepository(FavorisSent::class)->findBy(['admin' => $userId]);

        return $this->render('compte/index.html.twig', [
            'controller_name' => 'CompteController',
            'user' => $user,
            'role' => $role,
            'favoris' => $favoris
        ]);
    }

    #[Route('/compte/edit', name: 'app_compte_edit')]
    public function edit(Security $security,Request $request,EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {

        //Récupération des infos de l'utilisateur connecté
        $user = $security->getUser();
        $favoris = $entityManager->getRepository(FavorisSent::class)->findBy(['admin' => $user->getId()]);

        //Création du formulaire
        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);
        //Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //Encodage du mot de passe
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
            // Mise à jour des informations de l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Votre compte a bien été mis à jour');

            return $this->redirectToRoute('app_compte');
        }
            return $this->render('compte/edit.html.twig', [
            'controller_name' => 'CompteController',
           'form' => $form->createView(),
                 'favoris' => $favoris
        ]);
    }
}
