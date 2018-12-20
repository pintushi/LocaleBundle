<?php


namespace Pintushi\Bundle\OrderBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

final class CartItemType extends AbstractResourceType
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
    ) {
        parent::__construct($dataClass, $validationGroups);

        $this->dataTransformer = $dataTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serviceType', HiddenType::class, ['label' => 'pintushi.form.cart_item.service_type'])
            ->add('product', HiddenType::class, ['label' => 'pintushi.form.cart_item.product']);

        $builder->addModelTransformer($this->dataTransformer);
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pintushi_autot_item';
    }
}
