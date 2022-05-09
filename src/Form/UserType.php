<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class)
            ->add('firstName',TextType::class)
            ->add('lastName',TextType::class)
            ->add('phone',NumberType::class)
            ->add('email',EmailType::class)
            ->add('password')
            //->add('salt')
            ->add('country',TextType::class)
            ->add('birthDate')
            ->add('picture',FileType::class)
            ->add('address',TextType::class)
            ->add('role', ChoiceType::class, [
                'choices'  => [
                    'Select your role' => null,
                    'Player' => true,
                    'Admin' => false,
                ],
            ])
            ->add('gender', ChoiceType::class, [
                'choices'  => [
                    'Select your gender' => null,
                    'Male' => true,
                    'Female' => false,
                ],
            ]);
            
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
