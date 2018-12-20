<?php

namespace Pintushi\Bundle\OrderBundle\Form\Transformer;

use Pintushi\Bundle\ProductBundle\Doctrine\ORM\ProductRepositoryInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderItem;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Pintushi\Bundle\OrderBundle\Factory\CartItemFactory;

/**
 * @author   Vidy Videni   <videni@foxmail.com>
 */
class OrderItemTransformer implements DataTransformerInterface
{
    /**
     * @var CartItemFactory
     */
    protected $orderItemFactory;

    /**
     * @var EntityRepository
     */
    protected $serviceTypeEntityRepository;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    public function __construct(CartItemFactory $orderItemFactory, EntityRepository $serviceTypeEntityRepository, ProductRepositoryInterface $productRepository)
    {
        $this->orderItemFactory = $orderItemFactory;
        $this->serviceTypeEntityRepository = $serviceTypeEntityRepository;
        $this->productRepository = $productRepository;
    }


    public function transform($value)
    {
        if (null === $value) {
            return [];
        }

        if (!$value instanceof OrderItem) {
            throw new UnexpectedTypeException($value, OrderItem::class);
        }

        return [
            'serviceType' => $value->getServiceType()->getId(),
            'product' => $value->getProduct() ? $value->getProduct()->getId() : ''
        ];
    }


    public function reverseTransform($value)
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (!isset($value['serviceType'])) {
            return null;
        }

        if (!is_array($value) && !$value instanceof \Traversable && !$value instanceof \ArrayAccess) {
            throw new UnexpectedTypeException($value, '\Traversable or \ArrayAccess');
        }

        $serviceType = $this->serviceTypeEntityRepository->find($value['serviceType']);

        if (!$serviceType) {
            throw new InvalidArgumentException('The service type is not found');
        }
        $orderItem = $this->orderItemFactory->createNew();

        if (!empty($value['product']) && $product = $this->productRepository->find($value['product'])) {
            $orderItem->setProduct($product);
        }
        $orderItem->setServiceType($serviceType);

        return $orderItem;
    }
}