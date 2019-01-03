<?php

namespace Pintushi\Bundle\SmsBundle\Form\Type\Configuration;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class AliyunGatewayConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('accessKeyId', TextType::class, [
                'property_path' => '[access_key_id]',
                'constraints' => [
                    new NotBlank(['groups' => ['pintushi']]),
                ],
            ])
            ->add('accessKeySecret', TextType::class, [
                'property_path' => '[access_key_secret]',
                'constraints' => [
                    new NotBlank(['groups' => ['pintushi']]),
                ],
            ])
            ->add('signName', TextType::class, [
                'property_path' => '[sign_name]',
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
        return 'pintushi_sms_aliyun_gateway_configuration';
    }
}
