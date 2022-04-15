<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateRecType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('descriptionR')
            ->add('type',ChoiceType::class,array(
                    'choices'  => array(
                        'Contenu inapproprié'   => 'Mauvais Service',
                        'Fonctionnalité du site ' => 'Fonctionnalite du site',
                        'Retards et Changements'  => 'Retards et Changements',
                    )
                )
            )
            ->add('etatR',ChoiceType::class,array(
                    'choices'  => array(
                        'nontraitée'   => 'nontraitée',
                        'traitée ' => 'traitée'
                    )
                )
            )
            ->add('email')
            ->add('dateR')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
