<?php

declare(strict_types=1);

namespace Pintushi\Bundle\CustomerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Videni\Bundle\RestBundle\Form\DataTransformer\EntityToIdTransformer;
use Pintushi\Bundle\CustomerBundle\Entity\CustomerGroup;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type as FormTypes;

final class CustomerType extends AbstractType
{
    private $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', FormTypes\TextType::class)
            ->add('lastName', FormTypes\TextType::class)
            ->add('email', FormTypes\EmailType::class)
            ->add('birthday', FormTypes\BirthdayType::class)
            ->add('gender', GenderType::class)
            ->add('phoneNumber', FormTypes\TextType::class)
            ->add('group', FormTypes\TextType::class)
            ->add('plainPassword', FormTypes\PasswordType::class)
            ->add('enabled', FormTypes\CheckboxType::class)
        ;

        $builder->get('group')->addModelTransformer(new EntityToIdTransformer($this->entityManager, CustomerGroup::class));
    }
}
