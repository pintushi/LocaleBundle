<?php

namespace Pintushi\Bundle\ShippingBundle\Form\Type;

use Pintushi\Bundle\PromotionBundle\Form\Type\FixedCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Pintushi\Bundle\ShippingBundle\Entity\ShippingMethodInterface;

final class ShippingMethodCollectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entries' => [ShippingMethodInterface::TYPE_SHOP, ShippingMethodInterface::TYPE_HOME],
            'entry_name' => function ($entry) {
                return $entry;
            },
            'entry_options' => function ($entry) {
                return ['type' => $entry];
            },
            'error_bubbling' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FixedCollectionType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pintushi_shipping_method_collection';
    }
}
