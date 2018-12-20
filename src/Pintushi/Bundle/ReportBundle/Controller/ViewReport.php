<?php

namespace Pintushi\Bundle\ReportBundle\Controller;

use Pintushi\Bundle\ReportBundle\Entity\Report;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Pintushi\Bundle\ReportBundle\Repository\ReportRepository;
use Pintushi\Bundle\ReportBundle\Provider\ReportConfigProvider;
use Symfony\Component\HttpFoundation\JsonResponse;

class ViewReport extends Controller
{
    use  ReportViewTrait;

    private $validator;
    private $reportRepository;
    private $reportConfigProvider;

    public function __construct(
        ReportRepository $reportRepository,
        ReportConfigProvider $reportConfigProvider
    ) {
        $this->reportRepository = $reportRepository;
        $this->reportConfigProvider = $reportConfigProvider;
    }


    /**
     * @Route(
     *  name="api_admin_report_view",
     *  path="/reports/{id}",
     *  methods={"GET"},
     *  defaults={
     *   "_api_receive"=false,
     *   "_api_resource_class"= Report::class,
     *   "_api_item_operation_name"="get",
     *  }
     * )
     */
    public function view($id)
    {
        $report = $this->findOr404($id);

        $this->denyAccessUnlessGranted('VIEW', $report, 'You are not allowed to access this report');

        return new JsonResponse($this->createReportView($report, $this->reportConfigProvider->getConfigGroups($report)), Response::HTTP_OK);
    }

    private function findOr404($id)
    {
        $report = $this->reportRepository->findOneById($id);
        if (!$report) {
            throw $this->createNotFoundException(sprintf('The report %s is not existed', $id));
        }

        return $report;
    }
}
