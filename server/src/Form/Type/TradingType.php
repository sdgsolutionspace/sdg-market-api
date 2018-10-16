<?php

/**
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */

namespace App\Form\Type;

use App\Entity\Trading;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class TradingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('transactionUtcDatetime', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('numberOfTokens')
            ->add('sellOffer', SellOfferType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trading::class,
        ]);
    }
}
