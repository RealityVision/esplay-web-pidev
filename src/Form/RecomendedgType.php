<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recomendedg;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecomendedgType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('category', EntityType::class, [
                'class' => Category::class, 'choice_label' => 'Category_name'
            ])
            ->add('platform')
            ->add('url')
            ->add('prix');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recomendedg::class,
        ]);
    }
}
