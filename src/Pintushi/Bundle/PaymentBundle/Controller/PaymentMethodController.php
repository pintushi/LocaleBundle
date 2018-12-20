<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pintushi\Bundle\PaymentBundle\Repository\PaymentMethodRepository;
use Pintushi\Bundle\PaymentBundle\Entity\PaymentMethod;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Pintushi\Bundle\PaymentBundle\Form\Type\PaymentMethodType;
use Symfony\Component\Validator\ConstraintViolationList;
use Pintushi\Bundle\PayumBundle\Entity\GatewayConfig;

class PaymentMethodController extends Controller
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
     *  name="api_admin_payment_method_create",
     *  path="/payment_methods/new/{factory}",
     *  methods={"POST"},
     *  defaults={
     *   "_api_receive"=false,
     *   "_api_respond"=true,
     *   "_api_resource_class"= PaymentMethod::class,
     *   "_api_collection_operation_name"="post",
     *  }
     * )
     */
    public function create(Request $request, $factory)
    {
        $gatewayConfig = new GatewayConfig();
        $gatewayConfig->setFactoryName($factory);

        $paymentMethod = new PaymentMethod();
        $paymentMethod->setGatewayConfig($gatewayConfig);

        return $this->submit($request, $paymentMethod);
    }

    /**
     * @Route(
     *  name="api_admin_payment_method_update",
     *  path="/payment_methods/{id}",
     *  methods={"PUT"},
     *  defaults={
     *   "_api_receive"=true,
     *   "_api_resource_class"= PaymentMethod::class,
     *   "_api_item_operation_name"="put",
     *  }
     * )
     */
    public function update($data, Request $request)
    {
        return $this->submit($request, $data);
    }

    protected function submit($request, $paymentMethod)
    {
        $form = $this->formFactory->createNamed(
            '',
            PaymentMethodType::class,
            $paymentMethod,
            [
                'csrf_protection' => false,
                'validation_groups' => ['pintushi'],
                'method' => $request->getMethod()
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $paymentMethod = $form->getData();

            $em = $this->entityManger;
            $em->persist($paymentMethod);
            $em->flush();

            return $paymentMethod;
        }

        $violations = new ConstraintViolationList();
        foreach ($form->getErrors(true) as $error) {
            $violations->add($error->getCause());
        }

        return $violations;
    }

     /**
     * @Route(
     *  name="api_admin_get_payment_gateways",
     *  path="/payment_gateways",
     *  methods={"GET"},
     *  defaults={
     *       "_api_respond"=true,
     *  }
     * )
     */
    public function getPaymentGateways(Request $request)
    {
        return $this->getParameter('pintushi.gateway_factories');
    }
}
