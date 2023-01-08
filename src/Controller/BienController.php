<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\ContactForm;
use App\Form\BienType;
use App\Repository\BienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#TODO: Mettre à jour les routes comme ca il y a que l'admin qui modifie ca et pas les utilisateurs
#[Route('/admin/bien')]
class BienController extends AbstractController
{
    #[Route('/', name: 'app_bien_index', methods: ['GET'])]
    public function index(BienRepository $bienRepository): Response
    {
        $contactok = [];
        return $this->render('bien/index.html.twig', [
            'biens' => $bienRepository->findAll(),
            'contact' => $contactok

        ]);
    }

    #[Route('/new', name: 'app_bien_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BienRepository $bienRepository, EntityManagerInterface $em): Response
    {
        $contactok = [];
        $bien = new Bien();
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bienRepository->save($bien, true);

            //TODO: faire le mail automatique si le bien est validé
            $contact = $em->getRepository(ContactForm::class)->findAll();
            for ($i = 0; $i < count($contact); $i++) {
                $prix = $contact[$i]->getPrix();
                $surface = $contact[$i]->getSurface();
                $localisation = $contact[$i]->getLocalisation();

                $bienP = $bien->getPrix();
                $prixb = false;
                $surfaceb = false;
                $localisationb = false;
                if ($prix === '1000' && $bienP <= 1000) {
                    $prixb = true;
                } elseif ($prix === '1000-5000' && $bienP > 1000 && $bienP <= 5000) {
                    $prixb = true;
                } elseif ($prix === '5000-10000' && $bienP > 5000 && $bienP <= 10000) {
                    $prixb = true;
                } elseif ($prix === '10000' && $bienP > 10000) {
                    $prixb = true;
                } else {
                    $prixb = false;
                }
                $bienC = $bien->getSurface();

                if ($surface === '0-1' && $bienC <= 1) {
                    $surfaceb = true;
                } elseif ($surface === '1-5' && $bienC > 1 && $bienC <= 5) {
                    $surfaceb = true;
                } elseif ($surface === '5-10' && $bienC > 5 && $bienC <= 10) {
                    $surfaceb = true;

                } elseif ($surface === '10-20' && $bienC > 10 && $bienC <= 20) {
                    $surfaceb = true;
                } elseif ($surface === '20-50' && $bienC > 20 && $bienC <= 50) {
                    $surfaceb = true;
                } elseif ($surface === '50+' && $bienC > 50) {
                    $surfaceb = true;

                }

                if($prixb && $surfaceb){
                    echo "ok";
                    array_push($contactok, $contact[$i]);
                }
            }
            $html = '<html><head><title>Un nouveau bien est arrivé</title></head><body>';
            $html .= '<p>Un nouveau bien est arrivé sur le site, il correspond à vos critères de recherche</p>';
            $html .= '<p>Vous pouvez le consulter en cliquant sur le lien ci-dessous</p>';
            $html .= '<a href="http://localhost:8000/bien/' . $bien->getId() . '">Lien vers le bien</a>';
            $html .= '</body></html>';

            for($i = 0; $i < count($contactok); $i++){
                $transport = (new Swift_SmtpTransport('smtp.ionos.fr', 587))
                    ->setUsername('contact@mathiscapitaine.fr')
                    ->setPassword('gyfqid-Bigwow-wufqo2')
                ;

                $mailer = new Swift_Mailer($transport);
                #$html = file_get_contents('http://127.0.0.1:8000/favoris/');
                // Création de l'email
                $message = (new Swift_Message('Un nouveau bien sur la Safer'))
                    ->setFrom(['contact@mathiscapitaine.fr' => 'La safer'])
                    ->setTo([$contactok[$i]->getEmail()])
                    ->setBody($html,'text/html')
                    ->addPart('Contenu de l\'email en Texte', 'text/plain')
                ;

                // Envoi de l'email
                $mailer->send($message);
            }


            return $this->render('bien/index.html.twig', [
                    'biens' => $bienRepository->findAll(),
                    'contact' => $contactok]
            );
        }

        return $this->renderForm('bien/new.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bien_show', methods: ['GET'])]
    public function show(Bien $bien): Response
    {
        return $this->render('bien/show.html.twig', [
            'bien' => $bien,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bien_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bien $bien, BienRepository $bienRepository): Response
    {
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bienRepository->save($bien, true);

            return $this->redirectToRoute('app_bien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bien/edit.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bien_delete', methods: ['POST'])]
    public function delete(Request $request, Bien $bien, BienRepository $bienRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bien->getId(), $request->request->get('_token'))) {
            $bienRepository->remove($bien, true);
        }

        return $this->redirectToRoute('app_bien_index', [], Response::HTTP_SEE_OTHER);
    }
}
