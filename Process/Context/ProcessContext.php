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

namespace Sylius\Bundle\FlowBundle\Process\Context;

use Sylius\Bundle\FlowBundle\Process\ProcessInterface;
use Sylius\Bundle\FlowBundle\Process\Step\StepInterface;
use Sylius\Bundle\FlowBundle\Storage\StorageInterface;
use Sylius\Bundle\FlowBundle\Validator\ProcessValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class ProcessContext implements ProcessContextInterface
{
    protected ProcessInterface $process;

    protected ?StepInterface $currentStep = null;

    protected ?StepInterface $previousStep = null;

    protected ?StepInterface $nextStep = null;

    protected Request $request;

    protected int $progress = 0;

    protected bool $initialized = false;

    public function __construct(
        protected StorageInterface $storage,
    ) {
    }

    public function initialize(ProcessInterface $process, StepInterface $currentStep)
    {
        $this->process = $process;
        $this->currentStep = $currentStep;

        $this->storage->initialize(md5($process->getScenarioAlias()));

        $steps = $process->getOrderedSteps();

        foreach ($steps as $index => $step) {
            if ($step === $currentStep) {
                $this->previousStep = $steps[$index - 1] ?? null;
                $this->nextStep = $steps[$index + 1] ?? null;

                $this->calculateProgress($index);
            }
        }

        $this->initialized = true;

        return $this;
    }

    public function isValid(): bool
    {
        $this->assertInitialized();

        $validator = $this->process->getValidator();

        if ($validator instanceof ProcessValidatorInterface) {
            return $validator->isValid($this);
        }

        $history = $this->getStepHistory();

        return 0 === (is_countable($history) ? count($history) : 0) || in_array($this->currentStep->getName(), $history);
    }

    public function getProcess(): ProcessInterface
    {
        $this->assertInitialized();

        return $this->process;
    }

    public function getCurrentStep(): StepInterface
    {
        $this->assertInitialized();

        return $this->currentStep;
    }

    public function getPreviousStep(): StepInterface
    {
        $this->assertInitialized();

        return $this->previousStep;
    }

    public function getNextStep(): StepInterface
    {
        $this->assertInitialized();

        return $this->nextStep;
    }

    public function isFirstStep(): bool
    {
        $this->assertInitialized();

        return !$this->previousStep instanceof StepInterface;
    }

    public function isLastStep(): bool
    {
        $this->assertInitialized();

        return !$this->nextStep instanceof StepInterface;
    }

    public function close(): void
    {
        $this->assertInitialized();

        $this->storage->clear();
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    public function setStorage(StorageInterface $storage): void
    {
        $this->storage = $storage;
    }

    public function getProgress(): int
    {
        $this->assertInitialized();

        return $this->progress;
    }

    public function getStepHistory(): array
    {
        return $this->storage->get('history', []);
    }

    public function setStepHistory(array $history): void
    {
        $this->storage->set('history', $history);
    }

    public function addStepToHistory($stepName): void
    {
        $history = $this->getStepHistory();
        $history[] = $stepName;
        $this->setStepHistory($history);
    }

    public function rewindHistory(): void
    {
        $history = $this->getStepHistory();

        while ($top = end($history)) {
            if ($top !== $this->currentStep->getName()) {
                array_pop($history);
            } else {
                break;
            }
        }

        if (0 === (is_countable($history) ? count($history) : 0)) {
            throw new NotFoundHttpException(sprintf('Step "%s" not found in step history.', $this->currentStep->getName()));
        }

        $this->setStepHistory($history);
    }

    public function setNextStepByName(string $stepAlias): void
    {
        $this->nextStep = $this->process->getStepByName($stepAlias);
    }

    /**
     * If context was not initialized, throw exception.
     *
     * @throws \RuntimeException
     */
    protected function assertInitialized(): void
    {
        if (!$this->initialized) {
            throw new \RuntimeException('Process context was not initialized');
        }
    }

    protected function calculateProgress(int $currentStepIndex): void
    {
        $this->progress = (int) floor(($currentStepIndex + 1) / $this->process->countSteps() * 100);
    }
}
