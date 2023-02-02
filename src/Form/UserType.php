<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => false
            ])
            ->add('firstname', TextType::class, [
                'required' => true,
                'label' => false
            ])
            ->add('lastname', TextType::class, [
                'required' => true,
                'label' => false
            ])
            ->add('sex', ChoiceType::class, [
                'choices' => array_flip(User::sexs),
                'required' => true,
                'label' => false
            ])
            ->add('telephone', TelType::class, [
                'required' => true,
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
