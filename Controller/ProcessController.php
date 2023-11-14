<?php

declare(strict_types=1);

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\FlowBundle\Controller;

use Sylius\Bundle\FlowBundle\Process\Context\ProcessContextInterface;
use Sylius\Bundle\FlowBundle\Process\Coordinator\CoordinatorInterface;
use Sylius\Bundle\FlowBundle\Process\Coordinator\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Process controller.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class ProcessController
{
    public function __construct(protected CoordinatorInterface $processCoordinator, protected ProcessContextInterface $processContext)
    {
    }

    /**
     * Build and start process for given scenario.
     * This action usually redirects to first step.
     */
    public function startAction(Request $request, string $scenarioAlias): Response
    {
        return $this->processCoordinator->start($scenarioAlias, $request->query);
    }

    /**
     * Execute display action of given step.
     *
     * @throws NotFoundHttpException
     */
    public function displayAction(Request $request, string $scenarioAlias, string $stepName): Response
    {
        $this->processContext->setRequest($request);

        try {
            return $this->processCoordinator->display($scenarioAlias, $stepName, $request->query);
        } catch (InvalidArgumentException $e) {
            throw new NotFoundHttpException('The step you are looking for is not found.', $e);
        }
    }

    /** Execute continue action of given step. */
    public function forwardAction(Request $request, string $scenarioAlias, string $stepName): Response
    {
        $this->processContext->setRequest($request);

        return $this->processCoordinator->forward($scenarioAlias, $stepName);
    }
}
