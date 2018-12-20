<?php

namespace Pintushi\Bundle\ReportBundle\Entity;

class ReportConfigItem
{
    protected $id;

    protected $name;

    protected $description;

    protected $group;

    protected $priority;

    protected $report;

    public function getId()
    {
        return $this->id;
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

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
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
     * @param mixed $grade
     */
    public function setGrade($grade): void
    {
        $this->grade = $grade;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group): void
    {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getReport(): ? Report
    {
        return $this->report;
    }

    /**
     * @param mixed $report
     *
     * @return self
     */
    public function setReport(?Report $report)
    {
        $this->report = $report;

        return $this;
    }

    public function __clone()
    {
        $this->report = null;
        $this->id = null;
    }
}
