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

use Sylius\Bundle\FlowBundle\Process\Process;
use Sylius\Bundle\FlowBundle\Process\ProcessInterface;
use Sylius\Bundle\FlowBundle\Process\Scenario\ProcessScenarioInterface;
use Sylius\Bundle\FlowBundle\Process\Step\StepInterface;
use Sylius\Bundle\FlowBundle\Validator\ProcessValidatorInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProcessBuilder implements ProcessBuilderInterface
{
    /** @var StepInterface[] */
    protected array $steps;

    protected ProcessInterface $process;

    public function __construct(
        protected ContainerInterface $container,
    ) {
    }

    public function build(ProcessScenarioInterface $scenario): ProcessInterface
    {
        $this->process = new Process();

        $scenario->build($this);

        return $this->process;
    }

    public function add(string $name, $step): self
    {
        $this->assertHasProcess();

        if (is_string($step)) {
            $step = $this->loadStep($step);
        }

        if (!$step instanceof StepInterface) {
            throw new \InvalidArgumentException('Step added via builder must implement "Sylius\Bundle\FlowBundle\Process\Step\StepInterface"');
        }

        if (!$step->isActive()) {
            return $this;
        }

        if ($step instanceof ContainerAwareInterface) {
            $step->setContainer($this->container);
        }

        $step->setName($name);

        $this->process->addStep($name, $step);

        return $this;
    }

    public function remove($name): void
    {
        $this->assertHasProcess();

        $this->process->removeStep($name);
    }

    public function has(string $name): bool
    {
        $this->assertHasProcess();

        return $this->process->hasStep($name);
    }

    public function setDisplayRoute($route)
    {
        $this->assertHasProcess();

        $this->process->setDisplayRoute($route);

        return $this;
    }

    public function setDisplayRouteParams(array $params)
    {
        $this->assertHasProcess();

        $this->process->setDisplayRouteParams($params);

        return $this;
    }

    public function setForwardRoute($route)
    {
        $this->assertHasProcess();

        $this->process->setForwardRoute($route);

        return $this;
    }

    public function setForwardRouteParams(array $params)
    {
        $this->assertHasProcess();

        $this->process->setForwardRouteParams($params);

        return $this;
    }

    public function setRedirect($redirect)
    {
        $this->assertHasProcess();

        $this->process->setRedirect($redirect);

        return $this;
    }

    public function setRedirectParams(array $params)
    {
        $this->assertHasProcess();

        $this->process->setRedirectParams($params);

        return $this;
    }

    public function validate($validator)
    {
        $this->assertHasProcess();

        if ($validator instanceof \Closure) {
            $validator = $this->container->get('sylius.process.validator')->setValidation($validator);
        }

        if (!$validator instanceof ProcessValidatorInterface) {
            throw new \InvalidArgumentException();
        }

        $this->process->setValidator($validator);

        return $this;
    }

    public function registerStep($alias, StepInterface $step): void
    {
        if (isset($this->steps[$alias])) {
            throw new \InvalidArgumentException(sprintf('Flow step with alias "%s" is already registered', $alias));
        }

        $this->steps[$alias] = $step;
    }

    public function loadStep(string $alias): StepInterface
    {
        if (!isset($this->steps[$alias])) {
            throw new \InvalidArgumentException(sprintf('Flow step with alias "%s" is not registered', $alias));
        }

        return $this->steps[$alias];
    }

    /**
     * If process do not exists, throw exception.
     *
     * @throws \RuntimeException
     */
    protected function assertHasProcess(): void
    {
        if (!$this->process) {
            throw new \RuntimeException('Process is not set');
        }
    }
}
