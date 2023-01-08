<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    #[Route('/resetpassword', name: 'app_forgot_password_request')]
    public function request(Request $request, EntityManagerInterface $em, ResetPasswordHelper $resetPasswordHelper): Response
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        // If the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the user's email address
            $email = $form->get('email')->getData();

            // Check if a user with that email exists
            $user = $em->getRepository(Admin::class)->findOneBy(['email' => $email]);
            if ($user === null) {
                // If no user was found, display an error message
                $this->addFlash('danger', 'Aucun utilisateur avec cette adresse e-mail n\'a été trouvé.');

                return $this->redirectToRoute('app_forgot_password_request');
            }

            // Generate a unique token and send it to the user by email
            $resetPasswordHelper->sendResetEmail($user);

            // Display a success message
            $this->addFlash('success', 'Un e-mail de réinitialisation de mot de passe vous a été envoyé. Veuillez vérifier votre boîte de réception.');
            return $this->redirectToRoute('app_forgot_password_request');
        }

        return $this->render('reset_password/index.html.twig', [
            'form' => $form->createView()
        ]);
    }



     #[Route('/resetpassword/{token}', name:'app_reset_password')]

    public function reset(Request $request, string $token, UserRepository $userRepository, ResetPasswordHelper $resetPasswordHelper): Response
    {
        // Check if the token is valid and get the corresponding user
        $user = $resetPasswordHelper->validateTokenAndGetUser($token);
        if ($user === null) {
            // If the token is invalid, display an error message
            $this->addFlash('danger', 'Ce lien de réinitialisation de mot de passe n\'est pas valide ou a expiré.');

            return $this->redirectToRoute('app_forgot_password_request');
        }

        // Build the form
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        // If the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the new password and hash it
            $plainPassword = $form->get('plainPassword')->getData();
            $password = $this->get('security.password_encoder')->encodePassword($user, $plainPassword);

            // Update the user's password
            $user->setPassword($password);
            $userRepository->save($user);

            // Invalidate the token
            $resetPasswordHelper->invalidateToken($token);

            // Display a success message
            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');

            // Redirect the user to the login page
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
