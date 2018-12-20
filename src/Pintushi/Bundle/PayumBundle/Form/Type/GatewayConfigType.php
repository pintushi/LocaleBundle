<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle\Form\Type;

use Payum\Core\Model\GatewayConfigInterface;
use Pintushi\Bundle\CoreBundle\Form\Registry\FormTypeRegistryInterface;
use Pintushi\Bundle\PromotionBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class GatewayConfigType extends AbstractResourceType
{
    /**
     * @var FormTypeRegistryInterface
     */
    private $gatewayConfigurationTypeRegistry;

    /**
     * {@inheritdoc}
     *
     * @param FormTypeRegistryInterface $gatewayConfigurationTypeRegistry
     */
    public function __construct(
        string $dataClass,
        array $validationGroups = [],
        FormTypeRegistryInterface $gatewayConfigurationTypeRegistry
    ) {
        parent::__construct($dataClass, $validationGroups);

        $this->gatewayConfigurationTypeRegistry = $gatewayConfigurationTypeRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $factoryName = $options['data']->getFactoryName();

        $builder
            ->add('factoryName', TextType::class, [
                'label' => 'pintushi.form.gateway_config.type',
                'disabled' => true,
                'data' => $factoryName,
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($factoryName) {
                $gatewayConfig = $event->getData();

                if (!$gatewayConfig instanceof GatewayConfigInterface) {
                    return;
                }

                if (!$this->gatewayConfigurationTypeRegistry->has('gateway_config', $factoryName)) {
                    return;
                }

                $configType = $this->gatewayConfigurationTypeRegistry->get('gateway_config', $factoryName);

                $event->getForm()->add('config', $configType, [
                    'label' => false,
                    'auto_initialize' => false,
                ]);
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'pintushi_payum_gateway_config';
    }
}
