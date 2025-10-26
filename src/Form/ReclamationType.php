<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sujet',TextType::class)
            ->add('description',TextareaType::class)
            //->add('attachement')
            ->add('imageFile',VichImageType::class,array('data_class' => null),['label'=>'insert image'])
            ->add('email',EmailType::class)
            ->add('numeroTel')
             //->add('status')
            ->add('object', ChoiceType::class, [
                'choices' => [
                    'Objects' => [
                        'Technical issue' => 'Technical issue',
                        'Report a player' => 'Report a player',
                        'Other' => 'Other',
                    ]]])
//            ->add('Submit', SubmitType::class,
//                ['label' => 'ADD']
//            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
