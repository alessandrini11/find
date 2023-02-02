<?php

namespace App\Form;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false
            ])
            ->add('owner', TextType::class, [
                'label' => false,
                'required' => true
            ])
            ->add('type', ChoiceType::class, [
                'choices' => array_flip(Document::TYPES),
                'label' => false,
                'attr' => [
                    'class' => 'doc_type'
                ]
            ])
            ->add('idNumber', TextType::class, [
                'label' => false,
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
