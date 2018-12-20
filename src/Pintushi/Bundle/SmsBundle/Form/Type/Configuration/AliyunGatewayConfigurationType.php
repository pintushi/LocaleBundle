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
            ->add('access_key_id', TextType::class, [
                'constraints' => [
                    new NotBlank(['groups' => ['pintushi']]),
                ],
            ])
            ->add('access_key_secret', TextType::class, [
                'constraints' => [
                    new NotBlank(['groups' => ['pintushi']]),
                ],
            ])
            ->add('sign_name', TextType::class, [
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
