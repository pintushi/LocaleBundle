<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Form\Type;

use Pintushi\Bundle\PromotionBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Pintushi\Bundle\PaymentBundle\Entity\PaymentMethodInterface;
use Pintushi\Bundle\PayumBundle\Form\Type\GatewayConfigType;
use Pintushi\Component\Core\Formatter\StringInflector;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Overtrue\Pinyin\Pinyin;

final class PaymentMethodType extends AbstractResourceType
{
     /**
     * @var Pinyin
     */
    private $pinyin;

    public function __construct(string $dataClass, array $validationGroups = [], Pinyin $pinyin)
    {
        parent::__construct($dataClass, $validationGroups);
        $this->pinyin = $pinyin;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $gatewayFactory = $options['data']->getGatewayConfig();

        $builder
            ->add('position', IntegerType::class, [
                'required' => false,
                'label' => 'pintushi.form.shipping_method.position',
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
                'label' => 'pintushi.form.payment_method.enabled',
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'pintushi.form.payment_method.name',
            ])
            ->add('description', TextType::class, [
                'required' => true,
                'label' => 'pintushi.form.payment_method.description',
            ])
            ->add('gatewayConfig', GatewayConfigType::class, [
                'label' => false,
                'data' => $gatewayFactory,
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
                $paymentMethod = $event->getData();

                if (!$paymentMethod instanceof PaymentMethodInterface) {
                    return;
                }

                $gatewayConfig = $paymentMethod->getGatewayConfig();
                if (null === $gatewayConfig->getGatewayName()) {
                    $gatewayConfig->setGatewayName($this->pinyin->phrase($paymentMethod->getName(), '_'));
                }
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'pintushi_payment_method';
    }
}
