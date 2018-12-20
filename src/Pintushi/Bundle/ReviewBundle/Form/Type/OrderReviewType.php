<?php

declare(strict_types=1);

namespace Pintushi\Bundle\ReviewBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class OrderReviewType extends ReviewType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
             ->add('images', CollectionType::class, [
                'entry_type' => ReviewImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'pintushi.form.review.comment',
                'block_name' => 'entry',
             ])
        ;
    }
}
