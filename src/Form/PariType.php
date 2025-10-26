<?php

namespace App\Form;

use App\Entity\Joueur;
use App\Entity\Pari;
use App\Entity\Tmatchs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PariType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('iduser' , EntityType::class, [
            //          'class' => Joueur::class,
            //          'choice_label' => 'mail',
            //      ])
            ->add('montant')
            //->add('idequipe')
            // ->add('idmatch' , EntityType::class, [
            //     'class' => Tmatchs::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pari::class,
        ]);
    }
}
