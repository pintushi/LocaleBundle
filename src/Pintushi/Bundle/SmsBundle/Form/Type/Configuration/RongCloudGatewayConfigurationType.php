<?php

namespace Pintushi\Bundle\SmsBundle\Form\Type\Configuration;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class RongCloudGatewayConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('app_key', TextType::class, [
                'constraints' => [
                    new NotBlank(['groups' => ['pintushi']]),
                ],
            ])
            ->add('app_secret', TextType::class, [
                'constraints' => [
                    new NotBlank(['groups' => ['pintushi']]),
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pintushi_sms_rong_cloud_gateway_configuration';
    }
}
