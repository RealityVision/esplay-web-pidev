<?php

namespace App\Form;

use App\Entity\Commandeprod;
use App\Entity\Produit2;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeprodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idAcheteur')
            ->add('quantite')
            ->add('idProduit',EntityType::class,[
                'class' => Produit2::class,'choice_label' =>'nom'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commandeprod::class,
        ]);
    }
}
