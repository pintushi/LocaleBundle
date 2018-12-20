<?php

declare(strict_types=1);

namespace Pintushi\Bundle\ReviewBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pintushi\Bundle\ReviewBundle\Repository\OrderReviewRepository;
use Pintushi\Bundle\ReviewBundle\Entity\OrderReview;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Pintushi\Bundle\ReviewBundle\Form\TypeType;
use Doctrine\Common\Persistence\ObjectManager;
use Pintushi\Bundle\ReviewBundle\Form\Type\OrderReviewType;
use Symfony\Component\Validator\ConstraintViolationList;

class OrderReviewController extends Controller
{
    private $formFactory;
    private $orderReviewRepository;

    public function __construct(
        FormFactoryInterface $formFactory,
        OrderReviewRepository $orderReviewRepository
    ) {
        $this->formFactory = $formFactory;
        $this->orderReviewRepository = $orderReviewRepository;
    }

    /**
     * @Route(
     *  name="api_admin_order_review_update",
     *  path="/order_reviews/{id}",
     *  methods={"PUT"},
     *  defaults={
     *   "_api_receive"=true,
     *   "_api_resource_class"= OrderReview::class,
     *   "_api_item_operation_name"="put",
     *  }
     * )
     */
    public function update($data, Request $request)
    {
        return $this->submit($request, $data);
    }

    protected function submit($request, $orderReview)
    {
        $form = $this->formFactory->createNamed(
            '',
            OrderReviewType::class,
            $orderReview,
            [
                'csrf_protection' => false,
                'validation_groups' => ['pintushi'],
                'method' => $request->getMethod()
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $orderReview = $form->getData();

            $this->orderReviewRepository->add($orderReview);

            return $orderReview;
        }

        $violations = new ConstraintViolationList();
        foreach ($form->getErrors(true) as $error) {
            $violations->add($error->getCause());
        }

        return $violations;
    }
}
