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

use FOS\RestBundle\View\View;
use Sylius\Bundle\FlowBundle\Process\Context\ProcessContextInterface;
use Sylius\Bundle\FlowBundle\Process\Step\ActionResult;
use Sylius\Bundle\FlowBundle\Process\Step\StepInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Zach Badgett <zach.badgett@gmail.com>
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
interface ProcessValidatorInterface
{
    /** Message to display on invalid. */
    public function setMessage(string $message): self;

    /** Return message. */
    public function getMessage(): string;

    /** Set step name to go on error. */
    public function setStepName(string $stepName): self;

    /** Return step name to go on error. */
    public function getStepName(): string;

    /** Check validation. */
    public function isValid(ProcessContextInterface $processContext): bool;

    /** @return ActionResult|Response|View */
    public function getResponse(StepInterface $step);
}
