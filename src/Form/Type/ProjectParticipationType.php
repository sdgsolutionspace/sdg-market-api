<?php

namespace App\Form\Type;

use App\Entity\ProjectParticipation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ProjectParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numberOfTokens')
            ->add('calculationUtcDatetime', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('commitId')
            ->add('gitProject');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProjectParticipation::class,
        ]);
    }
}
