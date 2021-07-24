<?php

namespace App\Form;

use App\Entity\Gamme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('no')
            ->add('op')
            ->add('poste')
            ->add('description')
            ->add('setup')
            ->add('run')
            ->add('qth')
            ->add('qtemoh')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gamme::class,
        ]);
    }
}
