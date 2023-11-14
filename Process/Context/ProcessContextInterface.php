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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Interface for process context.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
interface ProcessContextInterface
{
    /** Initialize context with process and current step. */
    public function initialize(ProcessInterface $process, StepInterface $currentStep);

    /** Get process. */
    public function getProcess(): ProcessInterface;

    /** Get current step. */
    public function getCurrentStep(): StepInterface;

    /** Get previous step. */
    public function getPreviousStep(): StepInterface;

    /** Get next step. */
    public function getNextStep(): StepInterface;

    /** Is current step the first step? */
    public function isFirstStep(): bool;

    /** Is current step the last step? */
    public function isLastStep(): bool;

    /** Override the default next step. */
    public function setNextStepByName(string $stepAlias);

    /** Close context and clear all the data. */
    public function close();

    /** Is current flow valid? */
    public function isValid(): bool;

    /** Get storage. */
    public function getStorage(): StorageInterface;

    /** Set storage. */
    public function setStorage(StorageInterface $storage);

    /** Get current request. */
    public function getRequest(): Request;

    /** Set current request. */
    public function setRequest(Request $request);

    /** Get progress in percents. */
    public function getProgress(): int;

    /** The array contains the history of all the step names. */
    public function getStepHistory(): array;

    /** Set a new history of step names. */
    public function setStepHistory(array $history);

    /** Add the given name to the history of step names. */
    public function addStepToHistory(string $stepName);

    /**
     * Goes back from the end fo the history and deletes all step names until the current one is found.
     *
     * @throws NotFoundHttpException If the step name is not found in the history.
     */
    public function rewindHistory();
}
