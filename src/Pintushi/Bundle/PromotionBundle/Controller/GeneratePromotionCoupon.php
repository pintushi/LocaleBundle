<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Controller;

use Pintushi\Bundle\PromotionBundle\Form\Type\PromotionCouponGeneratorInstructionType;
use Pintushi\Bundle\PromotionBundle\Generator\PromotionCouponGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pintushi\Bundle\PromotionBundle\Repository\PromotionRepository;
use Pintushi\Bundle\PromotionBundle\Entity\Promotion;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;

class GeneratePromotionCoupon extends Controller
{
    private $promotionRepository;
    private $promotionCouponGenerator;
    private $formFactory;

    public function __construct(
        PromotionRepository $promotionRepository,
        FormFactoryInterface $formFactory,
        PromotionCouponGenerator $promotionCouponGenerator
    ) {
        $this->promotionRepository = $promotionRepository;
        $this->promotionCouponGenerator = $promotionCouponGenerator;
        $this->formFactory = $formFactory;
    }

     /**
     * @Route(
     *  name="api_admin_promotion_coupone_generate",
     *  path="/promotion/{promotionId}/coupon/generate",
     *  methods={"POST"},
     *  defaults={
     *   "_api_receive"=false,
     *   "_api_resource_class"= Promotion::class,
     *   "_api_collection_operation_name"="post",
     *  }
     * )
     */
    public function generateAction(Request $request, $promotionId)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        if (null === $promotion = $this->container->get('pintushi.repository.promotion')->find($promotionId)) {
            throw $this->createNotFoundException('Promotion not found.');
        }

        $form = $this->formFactory->create(PromotionCouponGeneratorInstructionType::class);

        if ($form->handleRequest($request)->isValid()) {
            $this->promotionCouponGenerator->generate($promotion, $form->getData());

            return $promotion;
        }

        return $form->getErrors();
    }
}
