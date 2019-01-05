<?php

declare(strict_types=1);

namespace Pintushi\Bundle\CustomerBundle\Form\Type;

use Pintushi\Bundle\UserBundle\Entity\UserInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class GenderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [
                '未知' => UserInterface::UNKNOWN_GENDER,
                '男' => UserInterface::MALE_GENDER,
                '女' => UserInterface::FEMALE_GENDER,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
