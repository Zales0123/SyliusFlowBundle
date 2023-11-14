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

/**
 * Tells the coordinator where to go next. Either go to the next
 * step or the one given in the constructor.
 */
class ActionResult
{
    public function __construct(private $stepName = null)
    {
    }

    /** @return string|null The name of the next step. */
    public function getNextStepName(): ?string
    {
        return $this->stepName;
    }
}
