<?php

namespace Pintushi\Bundle\EntityBundle\Resolver;

interface EntityTitleResolverInterface
{
    /**
     * Resolve entity title
     *
     * @param  object $entity
     * @return string|null
     */
    public function resolve($entity);
}
