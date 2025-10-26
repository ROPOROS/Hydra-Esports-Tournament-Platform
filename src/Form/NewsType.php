<?php

namespace App\Form;

use App\Entity\Jeu;
use App\Entity\News;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichImageType;


class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sujetN')
            ->add('text')
            //->add('image')
            ->add('imageFile',VichImageType::class,array('data_class' => null),['label'=>'insert image'])
            ->add('dateC',DateType::class ,array('label' => false, 'widget'=>'single_text'))
            ->add('dateF',DateType::class,array('label' => false, 'widget'=>'single_text'))
            ->add('idjeu',EntityType::class,[
                'class'=>Jeu::class,
                'choice_label'=>'nom'
            ])
//            ->add('Submit', SubmitType::class,
//                ['label' => 'ADD'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
