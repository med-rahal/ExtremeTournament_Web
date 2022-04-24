<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('descriptionR',TextareaType::class, array(
                'data' => '',
            ))
            ->add('type',ChoiceType::class,array(
                    'choices'  => array(
                    'Contenu inapproprié'   => 'Mauvais Service',
                    'Fonctionnalité du site ' => 'Fonctionnalite du site',
                    'Retards et Changements'  => 'Retards et Changements',
                    )
                )
            )
            ->add('email',HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
