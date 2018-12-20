<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PromotionType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'pintushi.form.promotion.name',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'pintushi.form.promotion.description',
                'required' => false,
            ])
            ->add('exclusive', CheckboxType::class, [
                'label' => 'pintushi.form.promotion.exclusive',
            ])
            ->add('usageLimit', IntegerType::class, [
                'label' => 'pintushi.form.promotion.usage_limit',
                'required' => false,
            ])
            ->add('startsAt', DateTimeType::class, [
                'label' => 'pintushi.form.promotion.starts_at',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('endsAt', DateTimeType::class, [
                'label' => 'pintushi.form.promotion.ends_at',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('priority', IntegerType::class, [
                'label' => 'pintushi.form.promotion.priority',
                'required' => false,
            ])
            ->add('couponBased', CheckboxType::class, [
                'label' => 'pintushi.form.promotion.coupon_based',
                'required' => false,
            ])
            ->add('rules', PromotionRuleCollectionType::class, [
                'label' => 'pintushi.form.promotion.rules',
            ])
            ->add('actions', PromotionActionCollectionType::class, [
                'label' => 'pintushi.form.promotion.actions',
             ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'pintushi_promotion';
    }
}
