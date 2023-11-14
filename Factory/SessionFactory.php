<?php

declare(strict_types=1);

namespace Sylius\Bundle\FlowBundle\Factory;

use Sylius\Bundle\FlowBundle\Storage\SessionFlowsBag;
use Symfony\Component\HttpFoundation\Session\SessionFactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SessionFactory implements SessionFactoryInterface
{
    public function __construct(private readonly SessionFactoryInterface $decoratedFactory)
    {
    }

    public function createSession(): SessionInterface
    {
        $session = $this->decoratedFactory->createSession();
        $session->registerBag(new SessionFlowsBag());

        return $session;
    }
}
