<?php

namespace Pintushi\Bundle\AddressBundle\Form\Type;

use Pintushi\Bundle\AddressBundle\Form\EventSubscriber\AddressTypeSubscriber;
use Pintushi\Bundle\PromotionBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Pintushi\Bundle\AddressBundle\Validator\Constraints\AddressConstraint;

class AddressType extends AbstractResourceType
{
    /**
     * @var AddressTypeSubscriber
     */
    protected $addressTypeSubscriber;

    public function __construct($dataClass, AddressTypeSubscriber $addressTypeSubscriber, array $validationGroups = [])
    {
        parent::__construct($dataClass, $validationGroups);

        $this->addressTypeSubscriber = $addressTypeSubscriber;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('regionCode', TextType::class, [
                'label' => 'pintushi.form.address.region',
                'error_bubbling' => false,
                'mapped' => true,
                'constraints'=> [
                    new AddressConstraint(['groups' => ["pintushi"]])
                ]
            ])
            ->add('street', TextType::class, [
                'label' => 'pintushi.form.address.street'
            ])
            ->addEventSubscriber($this->addressTypeSubscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pintushi_address';
    }
}
