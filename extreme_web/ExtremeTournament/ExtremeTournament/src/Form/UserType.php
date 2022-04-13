<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('username')
            ->add('date_naissance',BirthdayType::class)
            ->add('sexe')
            ->add('type',ChoiceType::class,array(
                    'choices'  => array(
                    'administrateur'   => 'administrateur',
                    'participant' => 'participant',
                    'proprietaire'  => 'proprietaire',
                ),

            ))
            ->add('email')
            ->add('passw')
            ->add('tel')
            ->add('adresse')
            ->add('image',FileType::class, [
                'label' => 'Please upload your image' ,
                'mapped' => false,


            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
