<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Form\Type\Action;

use Pintushi\Bundle\PromotionBundle\Form\Type\PromotionFilterCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class UnitFixedDiscountConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', TextType::class, [
                'label' => 'pintushi.form.promotion_action.fixed_discount_configuration.amount',
                'constraints' => [
                    new NotBlank(['groups' => ['pintushi']]),
                    new Type(['type' => 'numeric', 'groups' => ['pintushi']]),
                ],
            ])
            ->add('filters', PromotionFilterCollectionType::class, [
                'label' => false,
                'required' => false
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'pintushi_promotion_action_unit_fixed_discount_configuration';
    }
}
