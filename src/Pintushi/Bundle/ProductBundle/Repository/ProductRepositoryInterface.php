<?php

namespace Pintushi\Bundle\ProductBundle\Repository;

/**
 * @author Vidy Videni <foxmail.com>
 */
interface ProductRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findByName($name);

    /**
     * {@inheritdoc}
     */
    public function findByNamePart($phrase);
}
