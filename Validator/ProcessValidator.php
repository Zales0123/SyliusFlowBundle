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

namespace Sylius\Bundle\FlowBundle\Validator;

use Sylius\Bundle\FlowBundle\Process\Context\ProcessContextInterface;
use Sylius\Bundle\FlowBundle\Process\Step\StepInterface;

/**
 * @author Zach Badgett <zach.badgett@gmail.com>
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class ProcessValidator implements ProcessValidatorInterface
{
    /** @var callable */
    protected $validation;

    public function __construct(protected ?string $message = null, protected ?string $stepName = null, ?\Closure $validation = null)
    {
        $this->validation = $validation;
    }

    public function setStepName($stepName): self
    {
        $this->stepName = $stepName;

        return $this;
    }

    public function getStepName(): string
    {
        return $this->stepName;
    }

    public function setMessage($message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /** @param callable $validation */
    public function setValidation(\Closure $validation): self
    {
        $this->validation = $validation;

        return $this;
    }

    /** Get validation. */
    public function getValidation(): callable
    {
        return $this->validation;
    }

    public function isValid(ProcessContextInterface $processContext): bool
    {
        return (bool) call_user_func($this->validation);
    }

    public function getResponse(StepInterface $step)
    {
        if ($this->getStepName() !== '' && $this->getStepName() !== '0') {
            return $step->proceed($this->getStepName());
        }

        throw new ProcessValidatorException(400, $this->getMessage());
    }
}
