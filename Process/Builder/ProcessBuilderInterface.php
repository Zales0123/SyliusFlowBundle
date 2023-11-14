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

namespace Sylius\Bundle\FlowBundle\Process\Builder;

use Sylius\Bundle\FlowBundle\Process\ProcessInterface;
use Sylius\Bundle\FlowBundle\Process\Scenario\ProcessScenarioInterface;
use Sylius\Bundle\FlowBundle\Process\Step\StepInterface;
use Sylius\Bundle\FlowBundle\Validator\ProcessValidatorInterface;

/**
 * Process builder interface.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
interface ProcessBuilderInterface
{
    /** Build process by adding steps defined in scenario. */
    public function build(ProcessScenarioInterface $scenario): ProcessInterface;

    /**
     * Add a step with given name.
     *
     * @param string|StepInterface $step Step alias or instance
     */
    public function add(string $name, $step): self;

    /** Remove step with given name. */
    public function remove(string $name);

    /** Check whether or not process has given step. */
    public function has(string $name): bool;

    /** Set display route. */
    public function setDisplayRoute(string $route);

    /** Set additional forward route params. */
    public function setDisplayRouteParams(array $params);

    /** Set forward route. */
    public function setForwardRoute(string $route);

    /** Set additional forward route params. */
    public function setForwardRouteParams(array $params);

    /** Set redirection route after completion. */
    public function setRedirect(string $redirect);

    /** Set redirection route params. */
    public function setRedirectParams(array $params);

    /**
     * Validation of process, if returns false, process is suspended.
     *
     * @param \Closure|ProcessValidatorInterface $validator
     */
    public function validate($validator);

    /** Register new step. */
    public function registerStep(string $alias, StepInterface $step);

    /** Load step. */
    public function loadStep(string $alias): StepInterface;
}
