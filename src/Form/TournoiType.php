<?php

namespace App\Form;

use App\Entity\Tournoi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class TournoiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class)
            ->add('prix',NumberType::class)
            ->add('datedebut', DateType::class, [ 'widget' => 'single_text',
             'html5' => false,
              'attr' => ['class' => 'js-datepicker'],
              ])
            ->add('datefin', DateType::class, [ 'widget' => 'single_text',
             'html5' => false,
              'attr' => ['class' => 'js-datepicker'],
               ])
            ->add('details',TextareaType::class)
            ->add('equipes',HiddenType::class)
            ->add('heure',NumberType::class)
            ->add('phase',HiddenType::class)
            ->add('idjeu')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournoi::class,
        ]);
    }
}
