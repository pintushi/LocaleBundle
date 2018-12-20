<?php

namespace Pintushi\Bundle\AutoBundle\Controller;

use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;

class CarBrandController extends ResourceController
{

    public function hotAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $hotBrands = $this->repository->findHotBrands();

        $brands = $this->repository->findBy(['enabled'=>true]);

        $groupByFirstLetter=[];

        foreach ($brands as $brand) {
            /**
             * @var $brand \Pintushi\Bundle\AutoBundle\Entity\CarBrand
             */
            $groupByFirstLetter[$brand->getFirstLetter()][]=$brand;
        }

        ksort($groupByFirstLetter);

        $view = View::create();

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate(ResourceActions::INDEX . '.html'))
                ->setData([
                    'configuration' => $configuration,
                    'hots' => $hotBrands,
                    'brands' => $groupByFirstLetter,
                ]);
        }

        return $this->viewHandler->handle($configuration, $view);
    }
}
