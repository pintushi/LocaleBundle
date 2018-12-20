<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PaymentGatewayChoiceType extends AbstractType
{
    /**
     * @var array
     */
    private $gateways;

    /**
     * @param array $gateways
     */
    public function __construct(array $gateways)
    {
        $this->gateways = $gateways;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'choices' => array_flip($this->gateways),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'pintushi_payment_gateway_choice';
    }
}
