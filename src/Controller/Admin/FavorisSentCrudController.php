<?php

namespace App\Controller\Admin;

use App\Entity\FavorisSent;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FavorisSentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FavorisSent::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        //Permet de modifier les actions possibles sur la page de favoris, comme ca on ne peux pas en éditer un ou créer
        return $actions
            // ...
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->disable(Crud::PAGE_EDIT, Action::EDIT)
            ;
    }
    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
