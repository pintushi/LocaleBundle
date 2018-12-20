<?php

declare(strict_types=1);

namespace Pintushi\Bundle\SmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SmsGatewayChoiceType extends AbstractType
{
    /**
     * @var array
     */
    private $gateways;

    public function __construct(array $gateways)
    {
        $this->gateways = $gateways;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => array_flip($this->gateways),
        ]);
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
        return 'pintushi_sms_gateway_choice';
    }
}
