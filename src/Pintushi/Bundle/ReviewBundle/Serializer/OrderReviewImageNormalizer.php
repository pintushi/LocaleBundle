<?php

declare(strict_types=1);

namespace Pintushi\Bundle\ReviewBundle\Serializer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Pintushi\Bundle\ReviewBundle\Entity\OrderReviewImage;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class OrderReviewImageNormalizer implements NormalizerInterface
{
    private $objectNormalizer;

    public function __construct(ObjectNormalizer $objectNormalizer)
    {
        $this->objectNormalizer = $objectNormalizer;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        return $this->objectNormalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($object, $format = null)
    {
        return is_object($object) && get_class($object) === OrderReviewImage::class;
    }
}
