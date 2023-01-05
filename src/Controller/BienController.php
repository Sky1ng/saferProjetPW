<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Form\BienType;
use App\Repository\BienRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        return $this->render('bien/index.html.twig', [
            'biens' => $bienRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bien_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BienRepository $bienRepository, EntityManagerInterface $em): Response
    {
        $bien = new Bien();
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bienRepository->save($bien, true);

            //TODO: faire le mail automatique si le bien est validé
            $contact = $em->getRepository(Contact::class)->findAll();
            for ($i = 0; $i < count($contact); $i++) {
                $prix = $contact[$i]->getPrix();
                $surface = $contact[$i]->getSurface();
                $localisation = $contact[$i]->getLocalisation();

                if ($prix === '1000' && $bien <= 1000) {

                } elseif ($prix === '1000-5000') {

                } elseif ($prix === '5000-10000') {
                    $resultsP = $em->getRepository(Bien::class)->createQueryBuilder('b')
                        ->where('b.prix > :prix')
                        ->andwhere('b.prix < :prix2')
                        ->setParameter('prix', 5000)
                        ->setParameter('prix2', 10000)
                        ->getQuery()
                        ->getResult();
                } elseif ($prix === '10000+') {
                    $resultsP = $em->getRepository(Bien::class)->createQueryBuilder('b')
                        ->where('b.prix > :prix')
                        ->setParameter('prix', 10000)
                        ->getQuery()
                        ->getResult();
                } else {
                    $resultsP = $em->getRepository(Bien::class)->findAll();

                }

                $resultsL = $em->getRepository(Bien::class)->createQueryBuilder('b')
                    ->where('b.localisation LIKE :localisation')
                    ->setParameter('localisation', $localisation . '%')
                    ->getQuery()
                    ->getResult();
                if($resultsP === null){
                    $resultsP = $em->getRepository(Bien::class)->findAll();
                }
            }



            return $this->redirectToRoute('app_bien_index', [], Response::HTTP_SEE_OTHER);
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
