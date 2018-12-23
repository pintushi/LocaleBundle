<?php

namespace Pintushi\Bundle\BlockBundle\Entity;

use Videni\Bundle\FileBundle\Annotation as FileAnnoation;

/**
 * @FileAnnoation\File()
 */
class Block
{
    protected $id;

    protected $label;

    protected $linkUrl;

    protected $blockContainer;

     /**
     * @FileAnnoation\Link()
     */
    protected $image;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getBlockContainer(): BlockContainer
    {
        return $this->blockContainer;
    }

    /**
     * @param mixed $blockContainer
     */
    public function setBlockContainer(BlockContainer $blockContainer): void
    {
        $this->blockContainer = $blockContainer;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }


    /**
     * @return mixed
     */
    public function getLinkUrl()
    {
        return $this->linkUrl;
    }

    /**
     * @param mixed $linkUrl
     */
    public function setLinkUrl($linkUrl)
    {
        $this->linkUrl = $linkUrl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     *
     * @return self
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
}
