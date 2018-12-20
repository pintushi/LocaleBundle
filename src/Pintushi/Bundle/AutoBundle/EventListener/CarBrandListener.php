<?php

namespace Pintushi\Bundle\AutoBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Overtrue\Pinyin\Pinyin;
use Pintushi\Bundle\AutoBundle\Entity\CarBrand;

/**
 * @author Vidy Videni <videni@foxmail.com>
 */
final class CarBrandListener
{
    /**
     * @var Pinyin
     */
    private $pinyin;

    /**
     * FirstLetterListener constructor.
     * @param Pinyin $pinyin
     */
    public function __construct(Pinyin $pinyin)
    {
        $this->pinyin = $pinyin;
    }

    public function prePersist(LifecycleEventArgs $event): void
    {
        $this->setFirstLetter($event);
    }

    public function preUpdate(LifecycleEventArgs $event): void
    {
        $this->setFirstLetter($event);
    }

    public function setFirstLetter(LifecycleEventArgs $event): void
    {
        $brand = $event->getEntity();
        if ($brand instanceof CarBrand) {
            $abbr = $this->pinyin->abbr(mb_substr($brand->getName(), 0, 1));
            $brand->setFirstLetter(strtoupper($abbr));
        }
    }
}
