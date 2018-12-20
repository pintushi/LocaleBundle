<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

final class PromotionActionCollectionType extends AbstractConfigurationCollectionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('entry_type', PromotionActionType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'pintushi_promotion_action_collection';
    }
}
