<?php

/**
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */

namespace App\Form\Type;

use App\Entity\SellOffer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SellOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numberOfTokens')
            ->add('sellPricePerToken')
            ->add('offerStartsUtcDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('offerExpiresAtUtcDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('project')
            ->add('seller');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SellOffer::class,
        ]);
    }
}
