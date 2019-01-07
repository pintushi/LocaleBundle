<?php

namespace Pintushi\Bundle\ReviewBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Pintushi\Bundle\PromotionBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormError;

final class ReviewImageType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', FileType::class, [
                'label' => 'pintushi.form.image.file'
        ]);

        $builder->addEventListener(FormEvents::SUBMIT, [$this, 'submit']);
    }

    public function submit(FormEvent $event)
    {
        $reviewImage = $event->getData();
        $form = $event->getForm();

        $file = $reviewImage->getFile();
        if ($file) {
            $reviewImage->setMineType($file->getMimeType());
        }

        if (!$file && !$reviewImage->getPath()) {
            $form->get('file')->addError(new FormError('请上传图片'));
        }
    }
}
