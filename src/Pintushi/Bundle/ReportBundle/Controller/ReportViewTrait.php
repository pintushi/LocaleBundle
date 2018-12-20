<?php

namespace Pintushi\Bundle\ReportBundle\Controller;

use Pintushi\Bundle\ReportBundle\Entity\Report;
use Symfony\Component\Serializer\SerializerInterface;

trait ReportViewTrait
{
    protected function createReportView(Report $report, $configs)
    {
        $reportView =  [
            'created_at' => $report->getCreatedAt()->format('Y-m-d H:i:s'),
            'review' => $report->getReview(),
            'customer' => [
                'id' => $report->getCustomer()->getId(),
                'full_name' => $report->getCustomer()->getFullName(),
            ],
            'auto' => [
                'id' => $report->getAuto()->getId(),
                'full_name' => $report->getAuto()->getFullName(),
            ],
            'creator'=> [
                "id" => $report->getCreator()->getId(),
                'full_name' => $report->getCreator()->getFullName(),
            ],
            'grades' => array_map(function ($grade) {
                return  [
                    "score" => $grade->getGrade(),
                    "id" => $grade->getId(),
                    "item" => [
                        "id" => $grade->getReportConfigItem()->getId()
                    ]
                ];
            }, array_values($report->getGrades()->toArray())),
            'groups' => array_map(function ($configGroup) {
                return  [
                    "id" => $configGroup->getId(),
                    "name" => $configGroup->getName(),
                    "priority" => $configGroup->getPriority(),
                    "items" =>  array_map(function ($configItem) {
                        return  [
                            "id" => $configItem->getId(),
                            "name" => $configItem->getName(),
                            "priority" => $configItem->getPriority(),
                        ];
                    }, $configGroup->getItems()->toArray())
                ];
            }, array_values($configs)),
        ];

        return $reportView;
    }
}
