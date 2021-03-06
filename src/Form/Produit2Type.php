<?php

namespace App\Form;

use App\Entity\CategoryP;
use App\Entity\Produit2;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Produit2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('categorie')
            ->add('date')
            ->add('image')
            ->add('prix')
            ->add('stockProduit')
            ->add('produit2')
            ->add('categoryP',EntityType::class,[
                'class' => CategoryP::class,'choice_label' =>'name'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit2::class,
        ]);
    }
}
