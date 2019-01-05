<?php

namespace Pintushi\Bundle\PromotionBundle\Form\Type\Rule;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class NthOrderConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nth', IntegerType::class, [
                'label' => 'pintushi.form.promotion_rule.nth_order_configuration.nth',
                'constraints' => [
                    new NotBlank(['groups' => ['pintushi']]),
                    new Type(['type' => 'numeric', 'groups' => ['pintushi']]),
                ],
            ])
        ;
    }
}
