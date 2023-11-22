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

namespace Sylius\Bundle\FlowBundle\Process;

use Sylius\Bundle\FlowBundle\Process\Step\StepInterface;
use Sylius\Bundle\FlowBundle\Validator\ProcessValidatorInterface;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
interface ProcessInterface
{
    public function getScenarioAlias(): string;

    public function setScenarioAlias(string $scenarioAlias);

    /** @return StepInterface[] */
    public function getSteps(): array;

    /** @param StepInterface[] $steps */
    public function setSteps(array $steps);

    /** @return StepInterface[] */
    public function getOrderedSteps(): array;

    /** Get first process step. */
    public function getFirstStep(): StepInterface;

    /** Get last step. */
    public function getLastStep(): StepInterface;

    /** Add step and name it. */
    public function addStep(string $name, StepInterface $step);

    /** Remove step. */
    public function removeStep(string $name);

    /** Has step with given name? */
    public function hasStep(string $name): bool;

    /** Count all steps. */
    public function countSteps(): int;

    /** Get validator. */
    public function getValidator(): ?ProcessValidatorInterface;

    /**
     * Set validator.
     *
     *
     * @return $this
     */
    public function setValidator(ProcessValidatorInterface $validator);

    /** Get redirection after complete. */
    public function getRedirect(): string;

    /** Set redirection after complete. */
    public function setRedirect(string $redirect);

    /** Get redirection route params after complete. */
    public function getRedirectParams(): array;

    /** Set redirection route params after complete. */
    public function setRedirectParams(array $params);

    /** Get display route. */
    public function getDisplayRoute(): ?string;

    /** Set display route. */
    public function setDisplayRoute(string $route);

    /** Get additional display route parameters. */
    public function getDisplayRouteParams(): array;

    /** Set additional display route params. */
    public function setDisplayRouteParams(array $params);

    /** Get forward route. */
    public function getForwardRoute(): string;

    /** Set forward route. */
    public function setForwardRoute(string $route);

    /** Get additional forward route parameters. */
    public function getForwardRouteParams(): array;

    /** Set additional forward route params. */
    public function setForwardRouteParams(array $params);

    /** Get step by index/order. */
    public function getStepByIndex(int $index): StepInterface;

    /** Get step by name. */
    public function getStepByName(string $index): StepInterface;
}
