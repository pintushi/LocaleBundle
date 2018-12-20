<?php


namespace Pintushi\Bundle\OrderBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class OrderItemType extends AbstractResourceType
{
    /**
     * @var DataTransformerInterface
     */
    private $dataTransformer;

    /**
     * @param string $dataClass
     * @param array $validationGroups
     * @param DataTransformerInterface $dataTransformer
     */
    public function __construct(
        string $dataClass,
        array $validationGroups = [],
        DataTransformerInterface $dataTransformer
    )
    {
        parent::__construct($dataClass, $validationGroups);

        $this->dataTransformer = $dataTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serviceType', HiddenType::class, ['label' => 'pintushi.form.checkout.maintenance.service_type'])
            ->add('product', HiddenType::class, ['label' => 'pintushi.form.checkout.maintenance.product_variant']);

        $builder->addModelTransformer($this->dataTransformer);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'validation_groups' => $this->validationGroups,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pintushi_order_item';
    }
}
