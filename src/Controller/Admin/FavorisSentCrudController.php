<?php

namespace App\Controller\Admin;

use App\Entity\FavorisSent;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FavorisSentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FavorisSent::class;
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
