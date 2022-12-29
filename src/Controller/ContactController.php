<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\Categorie;
use App\Entity\ContactForm;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, FormFactoryInterface $factory, EntityManagerInterface $entityManager): Response
    {




        $categorie=[];
        $br=$entityManager->getRepository(Categorie::class);
        $categorie=$br->findAll();
        $contact = new ContactForm();
        $form = $this->createForm(ContactFormType::class, $contact);


        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($contact);
            $entityManager->flush();
            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('app_contact');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),'categorie'=>$categorie

        ]);
    }
}
