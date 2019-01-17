<?php

namespace Pintushi\Bundle\AddressBundle\Form\Type;

use Pintushi\Bundle\AddressBundle\Form\EventSubscriber\AddressTypeSubscriber;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Pintushi\Bundle\AddressBundle\Validator\Constraints\AddressConstraint;
use Symfony\Component\Form\AbstractType;
use Pintushi\Bundle\AddressBundle\Entity\Address;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    /**
     * @var AddressTypeSubscriber
     */
    protected $addressTypeSubscriber;

    public function __construct(AddressTypeSubscriber $addressTypeSubscriber)
    {
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

    public function configureOptions(OptionsResolver $resolver)
    {
         $resolver->setDefaults(
            [
                'data_class' => Address::class
            ]
        );
    }
}
