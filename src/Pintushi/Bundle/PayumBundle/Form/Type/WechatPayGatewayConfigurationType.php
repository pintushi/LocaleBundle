<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

final class WechatPayGatewayConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('app_id', TextType::class, [
                'label' => 'pintushi.form.gateway_configuration.wechat_pay.app_id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'pintushi.gateway_config.wechat_pay.app_id.not_blank',
                        'groups' => 'pintushi',
                    ]),
                ],
            ])
            ->add('mch_id', TextType::class, [
                'label' => 'pintushi.form.gateway_configuration.wechat_pay.mch_id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'pintushi.gateway_config.wechat_pay.mch_id.not_blank',
                        'groups' => 'pintushi',
                    ]),
                ],
            ])
            ->add('api_key', TextType::class, [
                'label' => 'pintushi.form.gateway_configuration.wechat_pay.api_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'pintushi.gateway_config.wechat_pay.api_key.not_blank',
                        'groups' => 'pintushi',
                    ]),
                ],
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $data = $event->getData();

                $data['payum.http_client'] = '@pintushi.payum.http_client';
            })
        ;
    }
}
