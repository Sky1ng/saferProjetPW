<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\ContactForm;
use App\Form\ContactPage;

use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, FormFactoryInterface $factory, EntityManagerInterface $entityManager): Response
    {




        $categorie=[];
        $br=$entityManager->getRepository(Categorie::class);
        $categorie=$br->findAll();
        $contact = new ContactForm();
        $form = $this->createForm(ContactType::class, $contact);


        $form->handleRequest($request);




        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($contact);
            $entityManager->flush();
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),'categorie'=>$categorie
        ]);

    }
}
