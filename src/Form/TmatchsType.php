<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\Tmatchs;
use App\Entity\Tournoi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TmatchsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('etat')
            ->add('datematch', DateType::class, [ 
                'widget' => 'single_text',
                          'html5' => false,
                          'attr' => ['class' => 'js-datepicker'],
                ])
            //->add('score')
            ->add('heurematch')
            //->add('phase')
            // ->add('idequipea', EntityType::class, [
            //     'class' => Team::class,
            //     'choice_label' => 'nom',
            // ])
            // ->add('idtournoi', EntityType::class, [
            //     'class' => Tournoi::class,
            //     'choice_label' => 'nom',
            // ])
            // ->add('idequipeb', EntityType::class, [
            //     'class' => Team::class,
            //     'choice_label' => 'nom',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tmatchs::class,
        ]);
    }
}
