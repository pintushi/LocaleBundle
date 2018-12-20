<?php

namespace Pintushi\Bundle\ReportBundle\Controller;

use Pintushi\Bundle\ReportBundle\Entity\Report;
use Pintushi\Bundle\ReportBundle\Entity\ReportGrade;
use Pintushi\Bundle\ReportBundle\Request\UpdateReportRequest;
use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Pintushi\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Pintushi\Bundle\ReportBundle\Repository\ReportRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Pintushi\Bundle\ReportBundle\Provider\ReportConfigProvider;

class UpdateReport extends Controller
{
    use  ReportViewTrait;

    private $validator;
    private $reportRepository;
    private $authorizationChecker;
    private $tokenAccessor;
    private $objectManager;
    private $reportConfigProvider;

    public function __construct(
        ValidatorInterface $validator,
        ReportRepository $reportRepository,
        ObjectManager $objectManager,
        TokenAccessorInterface $tokenAccessor,
        ReportConfigProvider $reportConfigProvider
    ) {
        $this->validator = $validator;
        $this->reportRepository = $reportRepository;
        $this->objectManager = $objectManager;
        $this->tokenAccessor = $tokenAccessor;
        $this->reportConfigProvider = $reportConfigProvider;
    }

     /**
     * @Route(
     *  name="api_admin_report_update",
     *  path="/reports/{id}",
     *  methods={"PUT"},
     *  defaults={
     *   "_api_receive"=true,
     *   "_api_resource_class"= UpdateReportRequest::class,
     *   "_api_collection_operation_name"="put",
     *  }
     * )
     */
    public function update(UpdateReportRequest $data, $id)
    {
        $report = $this->findOr404($id);

        $this->denyAccessUnlessGranted('EDIT', $report, 'You are not allowed to access this report');

        $errors = $this->validator->validate($data, null, ["pintushi"]);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $grades = $report->getGrades()->toArray();
        foreach ($data->getGrades() as $grade) {
            if (isset($grades[$grade->id])) {
                $reportGrade = $grades[$grade->id];
                $reportGrade->setGrade($grade->score);

                $this->objectManager->persist($reportGrade);
            }
        }
        $report->setReview($data->review);

        $this->objectManager->persist($report);
        $this->objectManager->flush();

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
