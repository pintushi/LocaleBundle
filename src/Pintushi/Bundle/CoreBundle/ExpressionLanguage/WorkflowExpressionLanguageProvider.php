<?php

namespace Pintushi\Bundle\CoreBundle\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\Workflow\Registry;

class WorkflowExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    private $workflowRegistry;

    public function __construct(Registry $workflowRegistry)
    {
        $this->workflowRegistry = $workflowRegistry;
    }

    public function getFunctions()
    {
        return array(
            new ExpressionFunction('workflow_can', function ($object, $transitionName, $name = 'null') {
                return sprintf('(workflow_can(%s, %s, %s)', $object, $transitionName, $name);
            }, function ($arguments, $object, $transitionName, $name = null) {
                return $this->canTransition($object, $transitionName, $name);
            }),
        );
    }

    /**
     * Returns true if the transition is enabled.
     *
     * @param object $subject        A subject
     * @param string $transitionName A transition
     * @param string $name           A workflow name
     *
     * @return bool true if the transition is enabled
     */
    private function canTransition($subject, $transitionName, $name = null)
    {
        return $this->workflowRegistry->get($subject, $name)->can($subject, $transitionName);
    }
}
