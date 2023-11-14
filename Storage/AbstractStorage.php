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

namespace Sylius\Bundle\FlowBundle\Storage;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
abstract class AbstractStorage implements StorageInterface
{
    protected string $domain;

    public function initialize($domain)
    {
        $this->domain = $domain;

        return $this;
    }
}
