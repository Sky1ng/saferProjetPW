<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\ContactForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,['label'=>'Votre email'], ['attr'=> ['placeholder'=>'Tapez votre email']])
            ->add('prix')
            ->add('localisation')
            ->add('surface')
            ->add('categorie',\Symfony\Bridge\Doctrine\Form\Type\EntityType::class,array('class' => Categorie::class,'choice_label' => 'nom','multiple' => false,'expanded' => false, 'required' => false))
            ->add('keyword')
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactForm::class,
        ]);
    }
}
