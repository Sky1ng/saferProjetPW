<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategorieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Categorie::class;
    }


    public function configureFields(string $pageName): iterable
    {
        //Permet de créer le formulaire d'ajout d'une catégorie à notre volontée
        return [
            IdField::new('id')->hideOnForm(),
            CollectionField::new('Biens')->hideOnForm(),
            TextField::new('type','Nom de la catégorie en base de donnée'),
            TextField::new('nom','Nom de la catégorie affiché sur le site'),
        ];
    }

}
