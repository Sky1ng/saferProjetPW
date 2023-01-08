<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\FavorisSent;
use Doctrine\ORM\EntityManagerInterface;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route('/favoris')]
class FavorisController extends AbstractController
{


    #[Route('/', name: 'app_favoris_index', methods: ['GET'])]
    public function index(Security $security,SessionInterface $session,EntityManagerInterface $em): Response
    {
        //Récupération des biens favoris de l'utilisateur connecté par la session
        $biens = $em->getRepository(Bien::class)->findBy(['id' => $session->get('favoris')]);


        $favoris = [];
        $idbiens = [];
        if($security->getUser() != null){
            //Récupération des biens favoris de l'utilisateur connecté par la base de données
            $idFavoris = $em->getRepository(FavorisSent::class)->findBy(['admin' => $security->getUser()->getId()]);
            //Pour chaque favoris de l'utilisateur connecté on récupère l'id du bien
            for($i = 0; $i < count($idFavoris); $i++){
                $idbiens = array_merge($idbiens,$idFavoris[$i]->getBiens());
            }
            //On récupère les biens correspondant aux id récupérés
            $favoris = $em->getRepository(Bien::class)->findBy(['id' => $idbiens]);
        }
        return $this->render('favoris/index.html.twig', [
            'biens' => $biens,
            'favoris' => $favoris
        ]);
    }

    #[Route('/add/{id}', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function addFavoris(string $id,SessionInterface $session): Response
    {
        $tab = $session->get('favoris');
        $tab[] = $id;
        $tableau = array_unique($tab);
        $session->set('favoris', $tableau);

        return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/remove', name: 'app_admin_remove', methods: ['GET', 'POST'])]
    public function removeFavoris(SessionInterface $session): Response
    {
        $session->set('favoris', []);

        return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/remove/{id}', name: 'app_admin_removeById', methods: ['GET', 'POST'])]
    public function removeFavorisById(string $id,SessionInterface $session): Response
    {
        $tab = $session->get('favoris');

        $indice = array_search($id, $tab);

        if ($indice !== false) {
            unset($tab[$indice]);
        }
        $session->set('favoris', $tab);
        return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/addInBase', name: 'app_admin_addINBase', methods: ['GET', 'POST'])]
    public function addInDataBase(string $id,SessionInterface $session): Response
    {
        $tab = $session->get('id');

     //TODO: AJOUTER AU COMPTE DE L'UTILISATEUR CONNECTE LES FAVORIS + LE BOUTON
        return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/mail', name: 'app_admin_mail', methods: ['GET', 'POST'])]
    public function sendFavoris(Security $security, SessionInterface $session,EntityManagerInterface $em,Request $request):Response
    {
        if($session->get('favoris') == null){
            return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);

        }
        $tab = $session->get('favoris');
        $biens = $em->getRepository(Bien::class)->findBy(['id' => $session->get('favoris')]);

        $html = '<html><head><title>Page web</title></head><body>';
        $html .= '<h1>Bonjour, voici vos favoris.</h1>';
        $html .= '<br>';
        $html .= '<br>';
        foreach ($biens as $bien){
            $html .= '<p>Titre = '.$bien->getTitre().'</p>';
            $html .= '<p>Description = '.$bien->getDescription().'</p>';
            $html .= '<p>Prix = '.$bien->getPrix().'</p>';
            $html .= '<p>Surface = '.$bien->getSurface().'</p>';
            $html .= '<p>Type = '.$bien->getType().'</p>';
            $html .= '<br>';
            $html .= '-----------------------------------------------------';
        }

        $html .= '<br>';
        $html .= '<br>';
        $html .= 'Merci d\'utiliser la safer pour vos recherches' ;




        $transport = (new Swift_SmtpTransport('smtp.ionos.fr', 587))
            ->setUsername('contact@mathiscapitaine.fr')
            ->setPassword('gyfqid-Bigwow-wufqo2')
        ;

        $mailer = new Swift_Mailer($transport);
        #$html = file_get_contents('http://127.0.0.1:8000/favoris/');
        // Création de l'email
        $message = (new Swift_Message('Vos favoris sur la safer'))
            ->setFrom(['contact@mathiscapitaine.fr' => 'La safer'])
            ->setTo($request->request->get('email'))
            ->setBody($html,'text/html')
            ->addPart('Contenu de l\'email en Texte', 'text/plain')
        ;

        // Envoi de l'email
        $result = $mailer->send($message);

        $favorisAdmin = new FavorisSent();
        $favorisAdmin->setEmail($request->request->get('email'));
        $favorisAdmin->setBiens($tab);
        $favorisAdmin->setDate(new \DateTime());
        if($security->getUser() != null){
            $favorisAdmin->setAdmin($security->getUser());
        }
        $em->persist($favorisAdmin);
        $em->flush();
        $session->set('favoris', []);
        $this->addFlash('success', 'Email envoyé avec succès');
        return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
    }

}
