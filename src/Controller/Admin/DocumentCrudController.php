<?php

namespace App\Controller\Admin;

use App\Entity\Document;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DocumentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Document::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('owner'),
            ChoiceField::new('type')
                ->setChoices(array_flip(Document::TYPES)),
            TextField::new('idNumber'),
            TextareaField::new('description'),
            AssociationField::new('user'),
//            CollectionField::new('user')->hideWhenCreating(),
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updatedAt')->hideOnForm()
        ];
    }

//    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
//    {
//        if (!$entityInstance instanceof Document) return;
//        $entityInstance->setUser($this->getUser());
//        parent::persistEntity($entityManager, $entityInstance);
//    }


}
