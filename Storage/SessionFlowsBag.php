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

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class SessionFlowsBag extends AttributeBag
{
    final public const STORAGE_KEY = '_sylius_flow_bag';

    final public const NAME = '_sylius_flow_bag';

    public function __construct()
    {
        parent::__construct(self::STORAGE_KEY);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
