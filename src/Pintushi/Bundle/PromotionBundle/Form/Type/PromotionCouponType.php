<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

final class PromotionCouponType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', $this->type, array_merge(
                ['label' => 'pintushi.ui.code'],
                $this->options,
                ['disabled' => $disabled]
            ))
            ->add('usageLimit', IntegerType::class, [
                'label' => 'pintushi.form.promotion_coupon.usage_limit',
                'required' => false,
            ])
            ->add('expiresAt', DateType::class, [
                'label' => 'pintushi.form.promotion_coupon.expires_at',
                'widget' => 'single_text',
                'placeholder' => ['year' => '-', 'month' => '-', 'day' => '-'],
                'required' => false,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'pintushi_promotion_coupon';
    }
}
