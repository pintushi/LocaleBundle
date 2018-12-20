<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

final class PromotionRuleCollectionType extends AbstractConfigurationCollectionType
{
     /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('entry_type', PromotionRuleType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'pintushi_promotion_rule_collection';
    }
}
