<?php

namespace Pintushi\Bundle\ReportBundle\Entity;

class ReportGrade
{
    const STATE_NONE = 'none';
    const STATE_GOOD = 'good';
    const STATE_WARNING = 'warning';
    const INSPECTION_SATE_DANGER = 'danger';

    protected $id;

    /**
     * @var Report
     */
    protected $report;

    /**
     * @var integer
     */
    protected $grade = self::STATE_NONE;

    /**
     * @var ReportConfigItem
     */
    protected $reportConfigItem;

    public function getId(): int
    {
        return $this->id;
    }

    public function getReport(): ?Report
    {
        return $this->report;
    }

    public function setReport(?Report $report)
    {
        $this->report = $report;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param mixed $grade
     *
     * @return $this
     */
    public function setGrade($grade = self::STATE_NONE)
    {
        $this->grade = $grade;

        return $this;
    }

    public function getReportConfigItem(): ReportConfigItem
    {
        return $this->reportConfigItem;
    }

    /**
     * @param ReportConfigItem $reportConfigItem
     *
     * @return $this
     */
    public function setReportConfigItem(ReportConfigItem $reportConfigItem)
    {
        $this->reportConfigItem = $reportConfigItem;

        return $this;
    }

    public static function getStateList()
    {
        return array(
            'pintushi.ui.report.grade.none' => self::STATE_NONE,
            'pintushi.ui.report.grade.good' => self::STATE_GOOD,
            'pintushi.ui.report.grade.warning' => self::STATE_WARNING,
            'pintushi.ui.report.grade.danger' => self::INSPECTION_SATE_DANGER,
        );
    }

    //extra property that ensures that we can index ReportGrade
    protected $reportConfigItemId;

    public function getReportConfigItemId(): int
    {
        return $this->reportConfigItem->getId();
    }
}
