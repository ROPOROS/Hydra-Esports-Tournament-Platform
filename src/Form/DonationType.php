<?php

namespace App\Form;

use App\Entity\Donation;

use App\Entity\Joueur;
use App\Entity\Team;
use Doctrine\DBAL\Types\IntegerType;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;




class DonationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant',TextType::class, [
                'required' => true,
                'constraints' => [ new Assert\GreaterThan(0)],
            ])

            ->add('iduser',HiddenType::class,[

            ])
            ->add('idteam',EntityType::class,['class'=> Team::class, 'choice_label'=>'nom'
                //,'multiple' => true
                //, 'expanded' => true
            ])

            ->add('datedon',DateType::class,[ 'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'required'=>true,
                'constraints'=> [new Assert\GreaterThan('today')]
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Donation::class,
        ]);
    }
}
