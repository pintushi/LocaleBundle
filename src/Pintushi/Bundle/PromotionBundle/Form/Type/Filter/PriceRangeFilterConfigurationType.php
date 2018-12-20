<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Form\Type\Filter;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Type;

final class PriceRangeFilterConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('min', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Type(['type' => 'numeric', 'groups' => ['pintushi']]),
                ],
            ])
            ->add('max', TextType::class, [
                'required' => false,
                'constraints' => [
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
        return 'pintushi_promotion_action_filter_price_range_configuration';
    }
}
