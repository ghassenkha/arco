<?php

namespace App\Form;

use App\Entity\Besoin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BesoinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('no')
            ->add('description')
            ->add('s1')
            ->add('s2')
            ->add('s3')
            ->add('s4')
            ->add('s5')
            ->add('s6')
            ->add('s7')
            ->add('s8')
            ->add('s9')
            ->add('s10')
            ->add('s11')
            ->add('s12')
            ->add('s13')
            ->add('s14')
            ->add('s15')
            ->add('s16')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Besoin::class,
        ]);
    }
}
