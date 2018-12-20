<?php

namespace Pintushi\Bundle\CoreBundle\Serializer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use ApiPlatform\Core\Serializer\ItemNormalizer;

class EmptyDateTimeDenormalizer implements DenormalizerInterface
{
    private $decorated;

    public function __construct(
        ItemNormalizer $decorated
    ) {
        $this->decorated = $decorated;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $data = $this->removeEmptyDatetime($data, $class);

        return $this->decorated->denormalize($data, $class, $format, $context);
    }

    private function removeEmptyDatetime($data, $type)
    {
        //@todo: 在datetime为空字符串时，symfony的反序列化会抛出异常。
        unset($data['expires_at']);

        return $data;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type == User::class && isset($data['expires_at']) && $data['expires_at'] == '';
    }
}
