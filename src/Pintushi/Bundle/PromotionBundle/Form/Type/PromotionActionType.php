<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

final class PromotionActionType extends AbstractConfigurablePromotionElementType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('type', PromotionActionChoiceType::class, [
                'label' => 'pintushi.form.promotion_action.type',
                'attr' => [
                    'data-form-collection' => 'update',
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'pintushi_promotion_action';
    }

}
