<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Form\Type;

use Pintushi\Bundle\PromotionBundle\Form\Type\Filter\PriceRangeFilterConfigurationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PromotionFilterCollectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('price_range_filter', PriceRangeFilterConfigurationType::class, [
            'label' => 'pintushi.form.promotion_filter.price_range',
            'required' => false
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'pintushi_promotion_filters';
    }
}
