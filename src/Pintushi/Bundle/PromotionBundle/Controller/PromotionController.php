<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Controller;

use Pintushi\Bundle\PromotionBundle\Generator\PromotionCouponGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pintushi\Bundle\PromotionBundle\Repository\PromotionRepository;
use Pintushi\Bundle\PromotionBundle\Entity\Promotion;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Pintushi\Bundle\PromotionBundle\Form\TypeType;
use Doctrine\Common\Persistence\ObjectManager;
use Pintushi\Bundle\PromotionBundle\Form\Type\PromotionType;
use Symfony\Component\Validator\ConstraintViolationList;

class PromotionController extends Controller
{
    private $formFactory;
    private $entityManger;

    public function __construct(
        FormFactoryInterface $formFactory,
        ObjectManager $entityManger
    ) {
        $this->formFactory = $formFactory;
        $this->entityManger = $entityManger;
    }

     /**
     * @Route(
     *  name="api_admin_promotion_create",
     *  path="/promotions",
     *  methods={"POST"},
     *  defaults={
     *   "_api_receive"=false,
     *   "_api_respond"=true,
     *   "_api_resource_class"= Promotion::class,
     *   "_api_collection_operation_name"="post",
     *  }
     * )
     */
    public function create(Request $request)
    {
        $promotion = new Promotion();

        return $this->submit($request, $promotion);
    }

    /**
     * @Route(
     *  name="api_admin_promotion_update",
     *  path="/promotions/{id}",
     *  methods={"PUT"},
     *  defaults={
     *   "_api_receive"=true,
     *   "_api_resource_class"= Promotion::class,
     *   "_api_item_operation_name"="put",
     *  }
     * )
     */
    public function update($data, Request $request)
    {
        return $this->submit($request, $data);
    }

    protected function submit($request, $promotion)
    {
        $form = $this->formFactory->createNamed(
            '',
            PromotionType::class,
            $promotion,
            [
                'csrf_protection' => false,
                'validation_groups' => ['pintushi'],
                'method' => $request->getMethod()
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $promotion = $form->getData();

            $em = $this->entityManger;
            $em->persist($promotion);
            $em->flush();

            return $promotion;
        }

        $violations = new ConstraintViolationList();
        foreach ($form->getErrors(true) as $error) {
            $violations->add($error->getCause());
        }

        return $violations;
    }
}
