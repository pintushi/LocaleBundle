<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Exception;

final class UnsupportedTypeException extends \InvalidArgumentException
{
    /**
     * @param mixed $value
     * @param string $expectedType
     */
    public function __construct($value, string $expectedType)
    {
        parent::__construct(sprintf(
            'Expected argument of type "%s", "%s" given.',
            $expectedType,
            is_object($value) ? get_class($value) : gettype($value)
        ));
    }
}
