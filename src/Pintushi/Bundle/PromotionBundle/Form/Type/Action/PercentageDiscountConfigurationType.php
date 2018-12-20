<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Form\Type\Action;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

final class PercentageDiscountConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('percentage', PercentType::class, [
                'label' => 'pintushi.form.promotion_action.percentage_discount_configuration.percentage',
                'constraints' => [
                    new NotBlank(['groups' => ['sylius']]),
                    new Type(['type' => 'numeric', 'groups' => ['sylius']]),
                    new Range([
                        'min' => 0,
                        'max' => 1,
                        'minMessage' => 'pintushi.promotion_action.percentage_discount_configuration.min',
                        'maxMessage' => 'pintushi.promotion_action.percentage_discount_configuration.max',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'pintushi_promotion_action_percentage_discount_configuration';
    }
}
