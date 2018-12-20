<?php

declare(strict_types=1);

namespace Pintushi\Bundle\ReviewBundle\Form\Type;

use Pintushi\Bundle\PromotionBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

abstract class ReviewType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', ChoiceType::class, [
                'choices' => $this->createRatingList($options['rating_steps']),
                'label' => 'pintushi.form.review.rating',
                'expanded' => true,
                'multiple' => false,
                'constraints' => [new Valid()],
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'pintushi.form.review.comment',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'rating_steps' => 5,
        ]);
    }

    /**
     * @param int $maxRate
     *
     * @return array
     */
    private function createRatingList(int $maxRate): array
    {
        $ratings = [];
        for ($i = 1; $i <= $maxRate; ++$i) {
            $ratings[$i] = $i;
        }

        return $ratings;
    }
}
