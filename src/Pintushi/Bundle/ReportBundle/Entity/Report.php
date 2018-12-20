<?php

namespace Pintushi\Bundle\ReportBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Pintushi\Bundle\UserBundle\Entity\TimestampableTrait;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;

/**
 * @Config(
 *  defaultValues={
 *      "security"={
 *          "type"="ACL",
 *          "group_name"="",
 *          "category"="report",
 *      },
 *    "ownership"={
 *              "owner_type"="ORGANIZATION",
 *              "owner_field_name"="organization",
 *              "owner_column_name"="organization_id",
 *          },
 *  }
 * )
 */
class Report implements ReportInterface
{
    use OrganizationAwareTrait, TimestampableTrait;

    protected $id;

    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @var ArrayCollection
     */
    protected $grades;

    protected $creator;

    protected $auto;

    protected $customer;

    /**
     * @var string
     */
    protected $review;

    public function __construct()
    {
        $this->grades = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

   /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param mixed $creator
     */
    public function setCreator($creator): void
    {
        $this->creator = $creator;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    /**
     * @param $order
     * @return $this
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;
        $order->setReport($this);

        return $this;
    }

    public function getGrades(): Collection
    {
        return $this->grades;
    }

    /**
     * @param ReportGrade $grade
     * @return $this
     */
    public function addGrade(ReportGrade $grade)
    {
        if (!$this->grades->contains($grade)) {
            $this->grades->add($grade);
            $grade->setReport($this);
        }

        return $this;
    }

    /**
     * @param ReportGrade $grade
     *
     * @return $this
     */
    public function removeGrade(ReportGrade $grade)
    {
        if ($this->grades->contains($grade)) {
            $this->grades->removeElement($grade);
        }

        return $this;
    }

    public function getReview(): string
    {
        return $this->review;
    }

    /**
     * @param $review
     * @return $this
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuto()
    {
        return $this->auto;
    }

    /**
     * @param mixed $auto
     *
     * @return self
     */
    public function setAuto($auto)
    {
        $this->auto = $auto;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param mixed $customer
     *
     * @return self
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }
}
