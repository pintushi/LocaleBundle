<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Pintushi\Bundle\CoreBundle\Form\Registry\FormTypeRegistryInterface;
use Symfony\Component\Form\FormInterface;
use Pintushi\Bundle\PromotionBundle\Entity\ConfigurablePromotionElementInterface;

final class PromotionRuleType extends AbstractConfigurablePromotionElementType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('type', PromotionRuleChoiceType::class, [
                'label' => 'pintushi.form.promotion_rule.type',
                'attr' => [
                    'data-form-collection' => 'update',
                ],
            ])
        ;
    }
}
