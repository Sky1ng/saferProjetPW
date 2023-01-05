<?php

namespace App\Controller;

use App\Entity\Admin;
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
    public function index(Security $security): Response
    {
        $user = $security->getUser();
        return $this->render('compte/index.html.twig', [
            'controller_name' => 'CompteController',
            'user' => $user
        ]);
    }

    #[Route('/compte/edit', name: 'app_compte_edit')]
    public function edit(Security $security,Request $request,EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $security->getUser();
        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
            // Mise à jour des informations de l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_compte');
        }
            return $this->render('compte/edit.html.twig', [
            'controller_name' => 'CompteController',
           'form' => $form->createView()
        ]);
    }
}
