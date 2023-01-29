<?php

namespace App\Controller\Admin;

use App\Entity\Declaration;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DeclarationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Declaration::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('document'),
            AssociationField::new('user'),
            TextareaField::new('description'),
            NumberField::new('reward'),
            ChoiceField::new('status')
                ->setChoices(array_flip(Declaration::STATUS)),
            AssociationField::new('visitors'),
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updatedAt')->hideOnForm()
        ];
    }

}
