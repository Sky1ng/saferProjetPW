<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\FavorisSent;
use App\Form\FavorisSentType;
use App\Repository\FavorisSentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/favoris')]
class FavorisSentController extends AbstractController
{
    #[Route('/', name: 'app_favoris_sent_index', methods: ['GET'])]
    public function index(FavorisSentRepository $favorisSentRepository): Response
    {
        return $this->render('favoris_sent/index.html.twig', [
            'favoris_sents' => $favorisSentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_favoris_sent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FavorisSentRepository $favorisSentRepository): Response
    {
        $favorisSent = new FavorisSent();
        $form = $this->createForm(FavorisSentType::class, $favorisSent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $favorisSentRepository->save($favorisSent, true);

            return $this->redirectToRoute('app_favoris_sent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('favoris_sent/new.html.twig', [
            'favoris_sent' => $favorisSent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_favoris_sent_show', methods: ['GET'])]
    public function show(FavorisSent $favorisSent, EntityManagerInterface $em): Response
    {

        $biens = $em->getRepository(Bien::class)->findBy(['id' => $favorisSent->getBiens()]);

        return $this->render('favoris_sent/show.html.twig', [
            'favoris_sent' => $favorisSent,
            'biens' => $biens,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_favoris_sent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FavorisSent $favorisSent, FavorisSentRepository $favorisSentRepository): Response
    {
        $form = $this->createForm(FavorisSentType::class, $favorisSent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $favorisSentRepository->save($favorisSent, true);

            return $this->redirectToRoute('app_favoris_sent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('favoris_sent/edit.html.twig', [
            'favoris_sent' => $favorisSent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_favoris_sent_delete', methods: ['POST'])]
    public function delete(Request $request, FavorisSent $favorisSent, FavorisSentRepository $favorisSentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$favorisSent->getId(), $request->request->get('_token'))) {
            $favorisSentRepository->remove($favorisSent, true);
        }

        return $this->redirectToRoute('app_compte', [], Response::HTTP_SEE_OTHER);
    }
}