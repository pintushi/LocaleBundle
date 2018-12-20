<?php

namespace Pintushi\Bundle\ReportBundle\Controller;

use Pintushi\Bundle\ReportBundle\Entity\Report;
use Pintushi\Bundle\ReportBundle\Entity\ReportGrade;
use Pintushi\Bundle\ReportBundle\Request\CreateReportRequest;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use Pintushi\Bundle\ReportBundle\Provider\ReportConfigProvider;
use Pintushi\Bundle\OrderBundle\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Pintushi\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateReport extends Controller
{
    use ReportViewTrait;

    private $validator;
    private $orderRepository;
    private $reportConfigProvider;
    private $authorizationChecker;
    private $tokenAccessor;
    private $objectManager;

    public function __construct(
        ValidatorInterface $validator,
        OrderRepository $orderRepository,
        ObjectManager $objectManager,
        TokenAccessorInterface $tokenAccessor,
        ReportConfigProvider $reportConfigProvider
    ) {
        $this->validator = $validator;
        $this->orderRepository = $orderRepository;
        $this->objectManager = $objectManager;
        $this->tokenAccessor = $tokenAccessor;
        $this->reportConfigProvider = $reportConfigProvider;
    }

     /**
     * @Route(
     *  name="api_admin_report_create",
     *  path="/reports",
     *  methods={"POST"},
     *  defaults={
     *   "_api_receive"=true,
     *   "_api_resource_class"= CreateReportRequest::class,
     *   "_api_collection_operation_name"="post",
     *  }
     * )
     */
    public function create(CreateReportRequest $data)
    {
        $errors = $this->validator->validate($data, null, ["pintushi"]);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $order = $this->orderRepository->find($data->orderId);
        if (!$order) {
            $this->createNotFoundException(sprintf('The order %s is not found', $data->orderId));
        }

        $this->denyAccessUnlessGranted('VIEW', $order, 'You are not allowed to access this order');

        $report =  new Report();
        $report->setReview($data->review);
        $report->setCreator($this->tokenAccessor->getUser());
        $report->setOrganization($order->getOrganization());
        $report->setCustomer($order->getCustomer());
        $report->setAuto($order->getAuto());

        $reportConfigs = $this->reportConfigProvider->get($order->getOrganization());
        $configGroups = [];
        foreach ($data->getGrades() as $grade) {
            if (isset($reportConfigs[$grade->configItemId]) && $configItem = $reportConfigs[$grade->configItemId]) {
                $configGroup = $configItem->getGroup();

                $reportGrade =  new ReportGrade();
                $reportGrade->setGrade($grade->score);

                $clonedConfigItem = clone $configItem;
                $clonedConfigItem->setReport($report);

                $reportGrade->setReportConfigItem($clonedConfigItem);

                if (isset($configGroups[$configGroup->getId()])) {
                    $configGroups[$configGroup->getId()]->addItem($clonedConfigItem);
                } else {
                    $clonedConfigGroup = clone $configGroup;
                    $clonedConfigGroup->addItem($clonedConfigItem);
                    $clonedConfigGroup->setReport($report);

                    $this->objectManager->persist($clonedConfigGroup);

                    $configGroups[$configGroup->getId()] = $clonedConfigGroup;
                }

                $report->addGrade($reportGrade);
            }
        }

        $this->objectManager->persist($report);
        $this->objectManager->flush();

        return new JsonResponse($this->createReportView($report, $configGroups), Response::HTTP_CREATED);
    }
}
