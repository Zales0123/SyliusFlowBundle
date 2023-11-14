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

namespace Sylius\Bundle\FlowBundle\Process\Step;

use Sylius\Bundle\FlowBundle\Process\Context\ProcessContextInterface;

/**
 * Base step class.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
abstract class AbstractStep implements StepInterface
{
    /** Step name in current scenario. */
    protected ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function forwardAction(ProcessContextInterface $context)
    {
        return $this->complete();
    }

    public function isActive(): bool
    {
        return true;
    }

    public function complete(): ActionResult
    {
        return new ActionResult();
    }

    public function proceed($nextStepName): ActionResult
    {
        return new ActionResult($nextStepName);
    }
}
