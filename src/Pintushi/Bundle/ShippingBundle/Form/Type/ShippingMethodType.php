<?php

declare(strict_types=1);

namespace Pintushi\Bundle\ShippingBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Pintushi\Bundle\PromotionBundle\Form\Type\AbstractResourceType;

final class ShippingMethodType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class, [
                'label' => 'pintushi.form.shipping_method.description',
                'required' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'pintushi.form.shipping_method.enabled',
            ])
            ->add('amount', IntegerType::class, [
                'label' => 'pintushi.form.shipping_method.amount',
            ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options): void {
            $shippingMethod = $event->getData();
            if (!$shippingMethod instanceof $this->dataClass) {
                $event->setData(null);

                return;
            }

            $shippingMethod->setType($options['type']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pintushi_shipping_method';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'label' => function (Options $options) {
                    return 'pintushi.form.shipping_method.' . $options['type'];
                },
            ])
            ->setRequired('type')
            ->setAllowedTypes('type', 'string')
        ;
    }
}
