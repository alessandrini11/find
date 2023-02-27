<?php

namespace App\Form;

use App\Entity\Declaration;
use App\Entity\Document;
use App\Entity\Municipality;
use App\Entity\Town;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class DeclarationType extends AbstractType
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
            ->add('reward', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ',
                    'placeholder' => 'Recompense'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'choices' => array_flip(Declaration::STATUS),
                'label' => false,
                'attr' => [
                    'class' => 'border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5',
                    'placeholder' => 'Description',
                    'cols' => '10',
                    'rows' => '3'
                ]
            ])
            ->add('ville', EntityType::class, [
                'class' => Town::class,
                'mapped' => false,
                'label' => false,
                'required' => true ,
                'attr' => [
                    'class' => 'townselect select2'
                ],
            ])
            ->add('municipality', EntityType::class, [
                'class' => Municipality::class,
                'label' => false,
                'required' => true ,
                'attr' => [
                    'class' => 'municipalityselect select2'
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false
            ])
            ->add('owner', TextType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ',
                    'placeholder' => 'Nom du propriétaire'
                ]
            ])
            ->add('idNumber', TextType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ',
                    'placeholder' => 'Identifiant'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => array_flip(Document::TYPES),
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '
                ],
            ])
//            ->add('document', EntityType::class, [
//                'class' => Document::class,
//                'label' => false,
//                'required' => true ,
//                'attr' => [
//                    'class' => 'doc_type'
//                ],
//                'query_builder' => function (EntityRepository $er){
//                    $qb = $er->createQueryBuilder('d')
//                        ->orderBy('d.owner', 'ASC');
//
//                    return $qb
//                        ->leftJoin('d.user', 'u')
//                        ->andWhere($qb->expr()->eq('u.id', ':userId'))
//                        ->setParameter('userId', $this->security->getUser()->getId())
//                        ;
//                },
//                'choice_label' => 'label'
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Declaration::class,
        ]);
    }
}
