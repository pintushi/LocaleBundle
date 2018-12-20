<?php

namespace Pintushi\Bundle\AutoBundle\Serializer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Pintushi\Bundle\AutoBundle\Repository\CarSeriesRepository;
use Pintushi\Bundle\AutoBundle\Entity\CarSeries;

class CarSeriesIdDenormalizer implements DenormalizerInterface
{
    private $carSeriesRepository;

    public function __construct(CarSeriesRepository $carSeriesRepository)
    {
        $this->carSeriesRepository = $carSeriesRepository;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return $this->carSeriesRepository->find($data['id']);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === CarSeries::class && isset($data['id']);
    }
}
