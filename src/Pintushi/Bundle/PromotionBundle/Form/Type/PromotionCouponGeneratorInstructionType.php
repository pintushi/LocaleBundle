<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;


final class PromotionCouponGeneratorInstructionType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', IntegerType::class, [
                'label' => 'pintushi.form.promotion_coupon_generator_instruction.amount',
            ])
            ->add('codeLength', IntegerType::class, [
                'label' => 'pintushi.form.promotion_coupon_generator_instruction.code_length',
            ])
            ->add('usageLimit', IntegerType::class, [
                'required' => false,
                'label' => 'pintushi.form.promotion_coupon_generator_instruction.usage_limit',
            ])
            ->add('expiresAt', DateType::class, [
                'required' => false,
                'label' => 'pintushi.form.promotion_coupon_generator_instruction.expires_at',
                'widget' => 'single_text',
            ])
        ;
    }
}
