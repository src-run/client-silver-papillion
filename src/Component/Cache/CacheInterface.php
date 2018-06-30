<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Cache;

use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

interface CacheInterface
{
    /**
     * @param AdapterInterface $adapter
     *
     * @return Cache
     */
    public function setAdapter(AdapterInterface $adapter = null) : Cache;

    /**
     * @param bool $enabled
     *
     * @return Cache
     */
    public function setEnabled(bool $enabled = true) : Cache;

    /**
     * @param int $ttl
     *
     * @return Cache
     */
    public function setTtl(int $ttl = 600) : Cache;

    /**
     * @param string $what
     *
     * @return bool
     */
    public function has(string $what) : bool;

    /**
     * @param string $what
     *
     * @return CacheItemInterface
     */
    public function get(string $what) : CacheItemInterface;

    /**
     * @param CacheItemInterface $what
     * @param bool               $deferred
     *
     * @return bool
     */
    public function set(CacheItemInterface $what, bool $deferred = false) : bool;

    /**
     * @return bool
     */
    public function commit() : bool;
}
