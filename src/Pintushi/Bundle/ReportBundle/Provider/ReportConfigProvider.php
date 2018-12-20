<?php

namespace Pintushi\Bundle\ReportBundle\Provider;

use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationInterface;
use Pintushi\Bundle\ReportBundle\Repository\ReportConfigItemRepository;
use Pintushi\Bundle\ReportBundle\Repository\ReportConfigGroupRepository;
use Pintushi\Bundle\ReportBundle\Entity\Report;

class ReportConfigProvider
{
    protected $reportConfigItemRepository;
    protected $reportConfigGroupRepository;

    public function __construct(
        ReportConfigItemRepository $reportConfigItemRepository,
        ReportConfigGroupRepository $reportConfigGroupRepository
    ) {
        $this->reportConfigItemRepository = $reportConfigItemRepository;
        $this->reportConfigGroupRepository = $reportConfigGroupRepository;
    }

    public function get(OrganizationInterface $organization)
    {
        return $this->reportConfigItemRepository->getReportConfig($organization);
    }

    public function getConfigGroups(Report $report)
    {
        return $this->reportConfigGroupRepository->getAllByReport($report);
    }
}
