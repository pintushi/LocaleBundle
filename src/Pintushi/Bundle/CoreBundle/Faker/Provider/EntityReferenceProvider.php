<?php

namespace Pintushi\Bundle\CoreBundle\Faker\Provider;

use Doctrine\Common\Persistence\ObjectManager;

class EntityReferenceProvider
{
    private $entityManger;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function entityReference(string $class, $method, $value)
    {
        return $this->getEntity($class, $method, $value);
    }

    public function entityId(string $class, $method, $value)
    {
        return $this->getEntity($class, $method, $value)->getId();
    }

    protected function getEntity(string $class, $method, $value)
    {
        if (!class_exists($class, true)) {
            return;
        }

        $repository = $this->entityManager->getRepository($class);
        if (!$repository) {
            return;
        }

         $result = call_user_func([$repository, $method], $value);

        if (!$result) {
            throw new \Exception(sprintf('No result found by %s %s %s', $class, $method, $value));
        }

         return $result;
    }
}
