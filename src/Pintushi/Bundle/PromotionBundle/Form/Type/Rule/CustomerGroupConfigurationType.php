<?php

namespace Pintushi\Bundle\PromotionBundle\Form\Type\Rule;

use Pintushi\Bundle\CustomerBundle\Form\Type\CustomerGroupChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class CustomerGroupConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('group_id', CustomerGroupChoiceType::class, [
                'label' => 'pintushi.form.promotion_rule.customer_group.group',
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
        return 'pintushi_promotion_rule_customer_group_configuration';
    }
}
