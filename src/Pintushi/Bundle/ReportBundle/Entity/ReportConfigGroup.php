<?php

namespace Pintushi\Bundle\ReportBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * @Config(
 *  defaultValues={
 *      "security"={
 *          "type"="ACL",
 *          "group_name"="",
 *          "category"="report",
 *      },
 *      "ownership"={
 *              "owner_type"="ORGANIZATION",
 *              "owner_field_name"="organization",
 *              "owner_column_name"="organization_id",
 *      },
 *  }
 * )
 */
class ReportConfigGroup implements OrganizationAwareInterface
{
    use OrganizationAwareTrait;

    protected $id;

    protected $items;

    protected $name;

    protected $priority;

    protected $report;

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
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function addItem(ReportConfigItem $item)
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setGroup($this);
        }

        return $this;
    }

    public function removeItem(ReportConfigItem $item)
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            $item->setGroup(null);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param mixed $priority
     */
    public function setPriority($priority): void
    {
        $this->priority = $priority;
    }

    /**
     *
     * if there is a report, it means this  group is a copy,
     * we have to cover the case, the old report won't be touched
     * if a report config group is deleted
     *
     * @return Report
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * @param mixed $report
     *
     * @return self
     */
    public function setReport($report)
    {
        $this->report = $report;

        return $this;
    }

    public function __clone()
    {
        $this->report = null;
        $this->id = null;
        $this->items = new ArrayCollection();
    }
}
