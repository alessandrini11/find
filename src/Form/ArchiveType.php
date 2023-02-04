<?php

namespace App\Form;

use App\Entity\Archive;
use App\Entity\Declaration;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class ArchiveType extends AbstractType
{
    private Security $security;

    /**
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('declaration', EntityType::class, [
                'class' => Declaration::class,
                'label' => false,
                'query_builder' => function (EntityRepository $er){
                    $qb = $er->createQueryBuilder('d')
                        ->andWhere('d.completed = :completed')
                        ->setParameter('completed', false);

                    return $qb
                        ->leftJoin('d.user', 'u')
                        ->andWhere($qb->expr()->eq('u.id', ':userId'))
                        ->setParameter('userId', $this->security->getUser()->getId())
                        ;
                },
                'attr' => [
                    'class' => 'doc_type'
                ],
                'choice_label' => 'label'
            ])
            ->add('actor', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er){
                    $qb = $er->createQueryBuilder('u');

                    return $qb
                        ->andWhere($qb->expr()->neq('u.id', ':userId'))
                        ->setParameter('userId', $this->security->getUser()->getId())
                        ;
                },
                'label' => false,
                'attr' => [
                    'class' => 'doc_type'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Archive::class,
        ]);
    }
}
