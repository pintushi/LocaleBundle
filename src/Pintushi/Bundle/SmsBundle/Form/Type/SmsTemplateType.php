<?php

declare(strict_types=1);

namespace Pintushi\Bundle\SmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class SmsTemplateType extends AbstractType
{
     /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('verification_code', TextType::class, [
                'constraints' => [
                    new NotBlank(['groups' => ['pintushi']]),
                ],
            ])
            ->add('service_code', TextType::class, [
                'constraints' => [
                    new NotBlank(['groups' => ['pintushi']]),
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'pintushi_sms_template';
    }
}
