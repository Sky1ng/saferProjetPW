<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    #[Route('/reset/password', name: 'app_reset_password')]
    public function index(): Response
    {
        return $this->render('reset_password/index.html.twig', [
            'controller_name' => 'ResetPasswordController',
        ]);
    }

    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, \Swift_Mailer $mailer)
    {
        // Si le formulaire de demande de réinitialisation de mot de passe a été soumis, envoyez un e-mail à l'utilisateur
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $userRepository->findOneByEmail($email);
            if ($user) {
                // Générez un jeton de réinitialisation de mot de passe et enregistrez-le en base de données
                $user->setResetPasswordToken(bin2hex(random_bytes(32)));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // Envoyez un e-mail à l'utilisateur contenant un lien de réinitialisation de mot de passe
                $message = (new \Swift_Message('Réinitialisation de votre mot de passe'))
                    ->setFrom('noreply@example.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'emails/reset_password.html.twig',
                            ['token' => $user->getResetPasswordToken()]
                        ),
                        'text/html'
                    );
                $mailer->send($message);
            }
            // Affichez une page de confirmation indiquant que l'e-mail de réinitialisation de mot de passe a été envoyé
            return $this->render('reset_password/check_email.html.twig');
        } else {
            // Affichez une erreur si aucun utilisateur n'a été trouvé avec l'adresse e-mail fournie
            $error = 'Aucun compte ne correspond à cette adresse e-mail';
        }


    // Si le formulaire n'a pas été soumis ou si une erreur s'est produite, affichez le formulaire de demande de réinitialisation de mot de passe
return $this->render('reset_password/request.html.twig', ['error' => $error ?? null]);
}

public function resetPasswordConfirm(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
{
    // Vérifiez si un utilisateur possède un jeton de réinitialisation de mot de passe valide
    $user = $userRepository->findOneByResetPasswordToken($token);
    if (!$user) {
        // Affichez une erreur si aucun utilisateur n'a été trouvé avec le jeton fourni
        throw $this->createNotFoundException('Ce jeton de réinitialisation de mot de passe n\'est pas valide ou a expiré');
    }

    // Si le formulaire de réinitialisation de mot de passe a été soumis, mettez à jour le mot de passe de l'utilisateur
    if ($request->isMethod('POST')) {
        $password = $request->request->get('password');
        $passwordConfirm = $request->request->get('password_confirm');
        if ($password === $passwordConfirm) {
            // Encodez le nouveau mot de passe et mettez-le à jour en base de données
            $user->setPassword($passwordEncoder->encodePassword($user, $password));
            $user->setResetPasswordToken(null);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Connectez l'utilisateur et redirigez-le vers la page d'accueil
            return $this->redirectToRoute('homepage');
        } else {
            // Affichez une erreur si les mots de passe ne correspondent pas
            $error = 'Les mots de passe ne correspondent pas';
        }
    }

    // Si le formulaire n'a pas été soumis ou si une erreur s'est produite, affichez le formulaire de réinitialisation de mot de passe
    return $this->render('reset_password/confirm.html.twig', ['error' => $error ?? null]);
}
}

