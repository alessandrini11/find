<?php

namespace App\Form;

use App\Entity\Declaration;
use App\Entity\Document;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeclarationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'label' => false,
                'required' => true
            ])
            ->add('reward', NumberType::class, [
                'label' => false,
                'required' => true
            ])
            ->add('status', ChoiceType::class, [
                'choices' => array_flip(Declaration::STATUS),
                'label' => false,
            ])
            ->add('document', EntityType::class, [
                'class' => Document::class,
                'label' => false,
                'required' => true ,
                'attr' => [
                    'class' => 'doc_type'
                ],
                'query_builder' => function (EntityRepository $er){
                    $qb = $er->createQueryBuilder('d')
                        ->orderBy('d.owner', 'ASC');

                    return $qb
                        ->leftJoin('d.user', 'u')
                        ->andWhere($qb->expr()->eq('u.id', ':userId'))
                        ->setParameter('userId', 3)
                        ;
                },
                'choice_label' => 'label'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Declaration::class,
        ]);
    }
}
