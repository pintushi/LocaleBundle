<?php

declare(strict_types=1);

namespace Pintushi\Bundle\SmsBundle\Form\Type;

use Pintushi\Bundle\CoreBundle\Form\Registry\FormTypeRegistryInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Pintushi\Bundle\SmsBundle\Entity\GatewayConfig;
use Symfony\Component\Form\AbstractType;

abstract class AbstractConfigurableGatewayConfigType extends AbstractType
{
    /**
     * @var FormTypeRegistryInterface
     */
    protected $formTypeRegistry;

    /**
     * {@inheritdoc}
     */
    public function __construct(FormTypeRegistryInterface $formTypeRegistry)
    {
        $this->formTypeRegistry = $formTypeRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
                $gateway = $this->getRegistryIdentifier($event->getForm(), $event->getData());
                if (null === $gateway) {
                    return;
                }

                $this->addConfigurationFields($event->getForm(), $this->formTypeRegistry->get($gateway, 'default'));
            })
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $gateway = $this->getRegistryIdentifier($event->getForm(), $event->getData());
                if (null === $gateway) {
                    return;
                }

                $event->getForm()->get('gateway')->setData($gateway);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                $data = $event->getData();

                if (!isset($data['gateway'])) {
                    return;
                }

                $this->addConfigurationFields($event->getForm(), $this->formTypeRegistry->get($data['gateway'], 'default'));
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('gateway', null)
            ->setAllowedTypes('gateway', ['string', 'null'])
        ;
    }

    /**
     * @param FormInterface $form
     * @param string $configurationType
     */
    protected function addConfigurationFields(FormInterface $form, string $configurationType): void
    {
        $form->add('configuration', $configurationType, [
            'label' => false,
        ]);
    }

     /**
     * @param FormInterface $form
     * @param mixed $data
     *
     * @return string|null
     */
    protected function getRegistryIdentifier(FormInterface $form, $data = null): ?string
    {
        if ($data instanceof GatewayConfig && null !== $data->getGateway()) {
            return $data->getGateway();
        }

        if (null !== $form->getConfig()->hasOption('gateway')) {
            return $form->getConfig()->getOption('gateway');
        }

        return null;
    }
}
