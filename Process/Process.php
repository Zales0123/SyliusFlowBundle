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

use Sylius\Bundle\FlowBundle\Process\Coordinator\InvalidArgumentException;
use Sylius\Bundle\FlowBundle\Process\Step\StepInterface;
use Sylius\Bundle\FlowBundle\Validator\ProcessValidatorInterface;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class Process implements ProcessInterface
{
    protected string $scenarioAlias;

    /** @var StepInterface[] */
    protected array $steps = [];

    /** @var StepInterface[] */
    protected array $orderedSteps = [];

    protected ?ProcessValidatorInterface $validator = null;

    /** Display action route. */
    protected string $displayRoute;

    /** Display action route params. */
    protected array $displayRouteParams = [];

    /** Forward action route. */
    protected string $forwardRoute;

    /** Forward action route params. */
    protected array $forwardRouteParams = [];

    /** Redirect route. */
    protected string $redirect;

    /** Redirect route params. */
    protected array $redirectParams = [];

    public function getScenarioAlias(): string
    {
        return $this->scenarioAlias;
    }

    public function setScenarioAlias($scenarioAlias): void
    {
        $this->scenarioAlias = $scenarioAlias;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }

    public function setSteps(array $steps): void
    {
        foreach ($steps as $name => $step) {
            $this->addStep($name, $step);
        }
    }

    public function getOrderedSteps(): array
    {
        return $this->orderedSteps;
    }

    public function getStepByIndex($index): StepInterface
    {
        if (!isset($this->orderedSteps[$index])) {
            throw new InvalidArgumentException(sprintf('Step with index %d. does not exist', $index));
        }

        return $this->orderedSteps[$index];
    }

    public function getStepByName(string $index): StepInterface
    {
        if (!$this->hasStep($index)) {
            throw new InvalidArgumentException(sprintf('Step with name "%s" does not exist', $index));
        }

        return $this->steps[$index];
    }

    public function getFirstStep(): StepInterface
    {
        return $this->getStepByIndex(0);
    }

    public function getLastStep(): StepInterface
    {
        return $this->getStepByIndex($this->countSteps() - 1);
    }

    public function countSteps(): int
    {
        return count($this->steps);
    }

    public function addStep($name, StepInterface $step): void
    {
        if ($this->hasStep($name)) {
            throw new InvalidArgumentException(sprintf('Step with name "%s" already exists', $name));
        }

        if (null === $step->getName()) {
            $step->setName($name);
        }

        $this->steps[$name] = $this->orderedSteps[] = $step;
    }

    public function removeStep($name): void
    {
        if (!$this->hasStep($name)) {
            throw new InvalidArgumentException(sprintf('Step with name "%s" does not exist', $name));
        }

        $index = array_search($this->steps[$name], $this->orderedSteps);

        unset($this->steps[$name], $this->orderedSteps[$index]);
        $this->orderedSteps = array_values($this->orderedSteps); //keep sequential index intact
    }

    public function hasStep($name): bool
    {
        return isset($this->steps[$name]);
    }

    public function getDisplayRoute(): string
    {
        return $this->displayRoute;
    }

    public function setDisplayRoute($route): void
    {
        $this->displayRoute = $route;
    }

    public function getDisplayRouteParams(): array
    {
        return $this->displayRouteParams;
    }

    public function setDisplayRouteParams(array $params): void
    {
        $this->displayRouteParams = $params;
    }

    public function getForwardRoute(): string
    {
        return $this->forwardRoute;
    }

    public function setForwardRoute($route): void
    {
        $this->forwardRoute = $route;
    }

    public function getForwardRouteParams(): array
    {
        return $this->forwardRouteParams;
    }

    public function setForwardRouteParams(array $params): void
    {
        $this->forwardRouteParams = $params;
    }

    public function getRedirect(): string
    {
        return $this->redirect;
    }

    public function setRedirect($redirect): void
    {
        $this->redirect = $redirect;
    }

    public function getRedirectParams(): array
    {
        return $this->redirectParams;
    }

    public function setRedirectParams(array $params): void
    {
        $this->redirectParams = $params;
    }

    public function getValidator(): ?ProcessValidatorInterface
    {
        return $this->validator;
    }

    public function setValidator(ProcessValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }
}
