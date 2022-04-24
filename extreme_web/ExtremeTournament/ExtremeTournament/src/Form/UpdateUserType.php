<?php

namespace App\Form;

use App\Entity\User;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateUserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('username')
            ->add('date_naissance',BirthdayType::class)
            ->add('sexe',ChoiceType::class,[
                'choices' => [
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'Others'=>'Others'

                ]])
            ->add('roles',ChoiceType::class,[
                'choices' => [
                    'Participant' => 'ROLE_USER',
                    'Proprietaire' => 'ROLE_PROPRIETAIRE',
                    'Administrateur' => 'ROLE_ADMIN'
                ],
                'multiple' => true,
                'label' => 'Rôles'
            ])
            ->add('email')
            ->add('tel')
            ->add('adresse')
            ->add('image',HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
