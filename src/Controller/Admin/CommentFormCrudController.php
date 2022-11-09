<?php

namespace App\Controller\Admin;

use App\Entity\CommentForm;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentFormCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CommentForm::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('content'),
            DateTimeField::new('created_at'),
            AssociationField::new('Form')
                ->setFormTypeOptions(['by_reference' => true,])
                ->autocomplete(),
            AssociationField::new('User')
                ->setFormTypeOptions(['by_reference' => true,])
                ->autocomplete()
        ];
    }
}
