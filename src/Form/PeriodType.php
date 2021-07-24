<?php

namespace App\Form;

use App\Entity\Period;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\ChoiceType;

class PeriodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('week',\Symfony\Component\Form\Extension\Core\Type\ChoiceType::class,
                array('choices' => array(
                    's1' => '1',
                    's2' => '2',
                    's3' => '3',
                    's4' => '4',
                    's5' => '5',
                    's6' => '6',
                    's7' => '7',
                    's8' => '8',
                    's9' => '9',
                    's10' => '10',
                    's11' => '11',
                    's12' => '12',
                    's13' => '13',
                    's14' => '14',
                    's15' => '15',
                    's16' => '16'),
                    'multiple'=>false,'expanded'=>true))
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'Calculer'],])
        ;


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Period::class,
        ]);
    }
}
