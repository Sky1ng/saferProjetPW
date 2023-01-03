<?php

namespace App\Controller\Admin;

use App\Entity\ContactForm;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ContactFormCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ContactForm::class;
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
