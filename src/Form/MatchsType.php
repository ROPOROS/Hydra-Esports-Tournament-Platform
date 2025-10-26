<?php

namespace App\Form;

use App\Entity\Matchs;
use App\Entity\Tournoi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idtournoi', EntityType::class, [
                'class' => Tournoi::class,
                'choice_label' => 'nom'
            ] )
            ->add('etat')
            ->add('datematch')
            ->add('score')
            ->add('heurematch')
            ->add('idequipea')
            ->add('idequipeb')
            ->add('phase')
        ;
    }

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matchs::class,
        ]);
    }
}
