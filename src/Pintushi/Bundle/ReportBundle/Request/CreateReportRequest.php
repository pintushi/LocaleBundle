<?php

namespace Pintushi\Bundle\ReportBundle\Request;

use Doctrine\Common\Collections\ArrayCollection;

class CreateReportRequest
{
    private $grades;

    public $review;

    public $orderId;

    public function __construct()
    {
        $this->grades = new ArrayCollection();
    }

    public function addGrade(CreateReportGrade $grade)
    {
        if (!$this->grades->contains($grade)) {
            $this->grades->add($grade);
        }

        return $this;
    }

    public function removeGrade(CreateReportGrade $grade)
    {
        if ($this->grades->contains($grade)) {
            $this->grades->removeElement($grade);
        }

        return $this;
    }

    public function getGrades()
    {
        return $this->grades;
    }
}
