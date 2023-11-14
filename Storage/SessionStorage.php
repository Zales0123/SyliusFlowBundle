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

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class SessionStorage extends AbstractStorage
{
    public function __construct(
        protected RequestStack $requestStack,
    ) {
    }

    public function get($key, $default = null): mixed
    {
        return $this->getBag()->get($this->resolveKey($key), $default);
    }

    public function set(string $key, string|array $value): void
    {
        $this->getBag()->set($this->resolveKey($key), $value);
    }

    public function has($key): bool
    {
        return $this->getBag()->has($this->resolveKey($key));
    }

    public function remove($key): void
    {
        $this->getBag()->remove($this->resolveKey($key));
    }

    public function clear(): void
    {
        $this->getBag()->remove($this->domain);
    }

    /** Get session flows bag. */
    private function getBag(): SessionBagInterface
    {
        return $this->requestStack->getSession()->getBag(SessionFlowsBag::NAME);
    }

    /** Resolve key for current domain. */
    private function resolveKey(string $key): string
    {
        return $this->domain . '/' . $key;
    }
}
