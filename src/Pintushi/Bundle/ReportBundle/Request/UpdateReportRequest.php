<?php

namespace Pintushi\Bundle\ReportBundle\Request;

use Doctrine\Common\Collections\ArrayCollection;

class UpdateReportRequest
{
    private $grades;

    public $review;

    public function __construct()
    {
        $this->grades = new ArrayCollection();
    }

    public function addGrade(UpdateReportGrade $grade)
    {
        if (!$this->grades->contains($grade)) {
            $this->grades->add($grade);
        }

        return $this;
    }

    public function removeGrade(UpdateReportGrade $grade)
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
