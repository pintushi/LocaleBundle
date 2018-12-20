<?php

namespace Pintushi\Bundle\ReviewBundle\Entity;

use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Config(
 *  defaultValues={
 *     "security"={
 *          "type"="ACL",
 *          "group_name"="",
 *          "category"="order",
 *      },
 *     "ownership"={
 *        "owner_type"="ORGANIZATION",
 *        "owner_field_name"="organization",
 *        "owner_column_name"="organization_id",
 *     },
 *  }
 * )
 */
class OrderReview extends Review implements OrganizationAwareInterface
{
    use OrganizationAwareTrait;

    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

  /**
     * {@inheritdoc}
     */
    public function hasImages(): bool
    {
        return !$this->images->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function hasImage(OrderReviewImage $image): bool
    {
        return $this->images->contains($image);
    }

    public function getImages()
    {
        return $this->images;
    }

    /**
     * {@inheritdoc}
     */
    public function addImage(OrderReviewImage $image): void
    {
        $image->setOwner($this);
        $this->images->add($image);
    }

    /**
     * {@inheritdoc}
     */
    public function removeImage(OrderReviewImage $image): void
    {
        if ($this->hasImage($image)) {
            $image->setOwner(null);
            $this->images->removeElement($image);
        }
    }
}
