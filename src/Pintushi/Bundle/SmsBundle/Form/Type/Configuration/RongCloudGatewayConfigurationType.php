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
            ->add('appKey', TextType::class, [
                'property_path' => '[app_key]',
                'constraints' => [
                    new NotBlank(['groups' => ['pintushi']]),
                ],
            ])
            ->add('appSecret', TextType::class, [
                'property_path' => '[app_secret]',
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
