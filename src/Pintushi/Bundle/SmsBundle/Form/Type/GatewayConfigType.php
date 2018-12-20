<?php

declare(strict_types=1);

namespace Pintushi\Bundle\SmsBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Pintushi\Bundle\CoreBundle\Form\Registry\FormTypeRegistryInterface;
use Symfony\Component\Form\FormInterface;
use Pintushi\Bundle\PromotionBundle\Entity\ConfigurablePromotionElementInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

final class GatewayConfigType extends AbstractConfigurableGatewayConfigType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('gateway', SmsGatewayChoiceType::class, [
                'label' => 'pintushi.form.promotion_rule.type',
                'attr' => [
                    'data-form-collection' => 'update',
                ],
            ])
            ->add('priority', IntegerType::class)
            ->add('enabled', CheckboxType::class)
            ->add('templates', SmsTemplateType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'pintushi_sms_gateway_config';
    }
}
