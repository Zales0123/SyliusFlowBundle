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
 * Storage interface.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
interface StorageInterface
{
    /**
     * Initializes storage for given domain.
     *
     *
     * @return $this
     */
    public function initialize(string $domain);

    /**
     * Checks if the storage has a value for a key.
     *
     * @param string $key A unique key
     *
     * @return bool Whether the storage has a value for this key
     */
    public function has(string $key): bool;

    /**
     * Returns the value for a key.
     *
     * @param string $key     A unique key
     *
     * @return mixed|null The value in the storage or default if set or null if not found
     */
    public function get(string $key, $default = null): mixed;

    /**
     * Sets a value in the storage.
     *
     * @param string $key   A unique key
     * @param string $value The value to storage
     */
    public function set(string $key, string|array $value);

    /**
     * Removes a value from the storage.
     *
     * @param string $key A unique key
     */
    public function remove(string $key);

    /** Clears all values from current domain. */
    public function clear();
}
