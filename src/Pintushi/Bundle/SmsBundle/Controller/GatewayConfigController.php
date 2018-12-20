<?php

declare(strict_types=1);

namespace Pintushi\Bundle\SmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pintushi\Bundle\SmsBundle\Repository\GatewayConfigRepository;
use Pintushi\Bundle\SmsBundle\Entity\GatewayConfig;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Pintushi\Bundle\SmsBundle\Form\TypeType;
use Doctrine\Common\Persistence\ObjectManager;
use Pintushi\Bundle\SmsBundle\Form\Type\GatewayConfigType;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * @Route("/admin")
 */
class GatewayConfigController extends Controller
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
     *  name="api_admin_gateway_config_create",
     *  path="/gateway_configs",
     *  methods={"POST"},
     *  defaults={
     *   "_api_receive"=false,
     *   "_api_respond"=true,
     *   "_api_resource_class"= GatewayConfig::class,
     *   "_api_collection_operation_name"="post",
     *  }
     * )
     */
    public function create(Request $request)
    {
        $gatewayConfig = new GatewayConfig();

        return $this->submit($request, $gatewayConfig);
    }

    /**
     * @Route(
     *  name="api_admin_gateway_config_update",
     *  path="/gateway_configs/{id}",
     *  methods={"PUT"},
     *  defaults={
     *   "_api_receive"=true,
     *   "_api_resource_class"= GatewayConfig::class,
     *   "_api_item_operation_name"="put",
     *  }
     * )
     */
    public function update($data, Request $request)
    {
        return $this->submit($request, $data);
    }

    protected function submit($request, $gatewayConfig)
    {
        $form = $this->formFactory->createNamed(
            '',
            GatewayConfigType::class,
            $gatewayConfig,
            [
                'csrf_protection' => false,
                'validation_groups' => ['pintushi'],
                'method' => $request->getMethod()
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $gatewayConfig = $form->getData();

            $em = $this->entityManger;
            $em->persist($gatewayConfig);
            $em->flush();

            return $gatewayConfig;
        }

        $violations = new ConstraintViolationList();
        foreach ($form->getErrors(true) as $error) {
            $violations->add($error->getCause());
        }

        return $violations;
    }
}
