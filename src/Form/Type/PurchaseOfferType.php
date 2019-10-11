<?php

namespace App\Form\Type;

use App\Entity\PurchaseOffer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class PurchaseOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numberOfTokens')
            ->add('purchasePricePerToken')
            ->add('offerStartsUtcDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('offerExpiresAtUtcDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('project')
            ->add('purchaser')
            ->add('goal');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PurchaseOffer::class,
        ]);
    }
}
