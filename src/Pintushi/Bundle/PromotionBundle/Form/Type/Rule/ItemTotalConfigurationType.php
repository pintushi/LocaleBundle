<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Form\Type\Rule;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class ItemTotalConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', TextType::class, [
                'label' => 'pintushi.form.promotion_rule.item_total_configuration.amount',
                'constraints' => [
                    new NotBlank(['groups' => ['pintushi']]),
                    new Type(['type' => 'numeric', 'groups' => ['pintushi']]),
                ]
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'pintushi_promotion_rule_item_total_configuration';
    }
}
