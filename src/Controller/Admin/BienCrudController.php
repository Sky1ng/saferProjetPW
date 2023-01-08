<?php

namespace App\Controller\Admin;

use App\Entity\Bien;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BienCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bien::class;
    }


    public function configureFields(string $pageName): iterable
    {
        //Permet de créer le formulaire d'ajout d'un bien à notre volontée
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextField::new('description'),
            NumberField::new('localisation','Code postal'),
            NumberField::new('surface'),
            NumberField::new('prix'),
            TextField::new('Type', 'Location/Vente')->setRequired(true),
            AssociationField::new('id_categorie', 'Catégorie'),
            ImageField::new('image')->setBasePath('uploads/images')->setUploadDir('public/uploads/images')->setUploadedFileNamePattern('[randomhash].[extension]')->setRequired(false),
        ];
    }


}
