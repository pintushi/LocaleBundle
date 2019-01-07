<?php

namespace Pintushi\Bundle\ReviewBundle\Entity;

use Videni\Bundle\FileBundle\Entity\AbstractFile;
use Videni\Bundle\FileBundle\Annotation as FileAnnoation;

/**
 * @FileAnnoation\File()
 */
class OrderReviewImage extends AbstractFile
{
    /**
     * @FileAnnoation\Link()
     */
    protected $path;
}
