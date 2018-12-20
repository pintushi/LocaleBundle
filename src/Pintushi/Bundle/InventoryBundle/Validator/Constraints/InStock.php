<?php

declare(strict_types=1);

namespace Pintushi\Bundle\InventoryBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
final class InStock extends Constraint
{
    /**
     * @var string
     */
    public $message = 'pintushi.cart_item.not_available';

    /**
     * @var string
     */
    public $stockablePath = 'stockable';

    /**
     * @var string
     */
    public $quantityPath = 'quantity';

    /**
     * {@inheritdoc}
     */
    public function validatedBy(): string
    {
        return 'pintushi_in_stock';
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
