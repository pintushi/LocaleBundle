<?php

declare(strict_types=1);

namespace Pintushi\Bundle\CustomerBundle\Form\Type;

use Pintushi\Bundle\CustomerBundle\Repository\CustomerGroupRepository;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CustomerGroupChoiceType extends AbstractType
{
    /**
     * @var CustomerGroupRepository
     */
    private $customerGroupRepository;

    /**
     * @param CustomerGroupRepository $customerGroupRepository
     */
    public function __construct(CustomerGroupRepository $customerGroupRepository)
    {
        $this->customerGroupRepository = $customerGroupRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['multiple']) {
            $builder->addModelTransformer(new CollectionToArrayTransformer());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => function (Options $options): array {
                return $this->customerGroupRepository->findAll();
            },
            'choice_value' => 'id',
            'choice_label' => 'name',
            'choice_translation_domain' => false,
            'label' => 'pintushi.form.customer.group',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'pintushi_customer_group_choice';
    }
}
