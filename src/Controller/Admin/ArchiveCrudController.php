<?php

namespace App\Controller\Admin;

use App\Entity\Archive;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArchiveCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Archive::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('declaration'),
            AssociationField::new('owner'),
            AssociationField::new('actor'),
            BooleanField::new('ownerValidation'),
            BooleanField::new('actorValidation'),
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updatedAt')->hideOnForm()
        ];
    }

}
