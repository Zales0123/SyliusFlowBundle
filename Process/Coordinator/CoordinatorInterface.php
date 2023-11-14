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

namespace Sylius\Bundle\FlowBundle\Process\Coordinator;

use FOS\RestBundle\View\View;
use Sylius\Bundle\FlowBundle\Process\Scenario\ProcessScenarioInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * This service coordinates the whole flow of process.
 * Executes steps and start flows.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
interface CoordinatorInterface
{
    public function start(string $scenarioAlias, ?ParameterBag $queryParameters = null): RedirectResponse;

    public function display(string $scenarioAlias, string $stepName, ?ParameterBag $queryParameters = null): Response|View;

    public function forward(string $scenarioAlias, string $stepName): Response|View;

    /** Register new process scenario. */
    public function registerScenario(string $alias, ProcessScenarioInterface $scenario);

    /** Load process scenario with given alias. */
    public function loadScenario(string $scenario): ProcessScenarioInterface;
}
