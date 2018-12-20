<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Form\Type;

use Pintushi\Bundle\PromotionBundle\Form\Type\AbstractResourceType;
use Pintushi\Bundle\PaymentBundle\Entity\PaymentInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class PaymentType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('method', PaymentMethodChoiceType::class, [
                'label' => 'pintushi.form.payment.method',
            ])
            ->add('amount', TextType::class, [
                'label' => 'pintushi.form.payment.amount',
            ])
            ->add('state', ChoiceType::class, [
                'choices' => [
                    'pintushi.form.payment.state.processing' => PaymentInterface::STATE_PROCESSING,
                    'pintushi.form.payment.state.failed' => PaymentInterface::STATE_FAILED,
                    'pintushi.form.payment.state.completed' => PaymentInterface::STATE_COMPLETED,
                    'pintushi.form.payment.state.new' => PaymentInterface::STATE_NEW,
                    'pintushi.form.payment.state.cancelled' => PaymentInterface::STATE_CANCELLED,
                    'pintushi.form.payment.state.refunded' => PaymentInterface::STATE_REFUNDED,
                ],
                'label' => 'pintushi.form.payment.state.header',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'pintushi_payment';
    }
}
