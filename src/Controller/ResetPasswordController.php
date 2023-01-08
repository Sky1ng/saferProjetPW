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

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();

            $user = $em->getRepository(Admin::class)->findOneBy(['email' => $email]);
            if ($user === null) {
                $this->addFlash('danger', 'Aucun utilisateur avec cette adresse e-mail n\'a été trouvé.');

                return $this->redirectToRoute('app_forgot_password_request');
            }

            $resetPasswordHelper->sendResetEmail($user);

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
        $user = $resetPasswordHelper->validateTokenAndGetUser($token);
        if ($user === null) {
            $this->addFlash('danger', 'Ce lien de réinitialisation de mot de passe n\'est pas valide ou a expiré.');

            return $this->redirectToRoute('app_forgot_password_request');
        }

        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $password = $this->get('security.password_encoder')->encodePassword($user, $plainPassword);

            $user->setPassword($password);
            $userRepository->save($user);

            $resetPasswordHelper->invalidateToken($token);

            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
