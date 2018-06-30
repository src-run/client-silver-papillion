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
use Symfony\Component\Cache\CacheItem;

class Cache implements CacheInterface
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var bool
     */
    private $enabled = true;

    /**
     * @var int
     */
    private $ttl = 600;

    /**
     * @param AdapterInterface|null $adapter
     */
    public function __construct(AdapterInterface $adapter = null)
    {
        $this->setAdapter($adapter);
    }

    /**
     * @param AdapterInterface $adapter
     *
     * @return Cache
     */
    public function setAdapter(AdapterInterface $adapter = null) : Cache
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * @param bool $enabled
     *
     * @return Cache
     */
    public function setEnabled(bool $enabled = true) : Cache
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @param int $ttl
     *
     * @return Cache
     */
    public function setTtl(int $ttl = 600) : Cache
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * @param string $what
     *
     * @return bool
     */
    public function has(string $what) : bool
    {
        if (!$this->enabled) {
            return false;
        }

        return $this->adapter->hasItem($what);
    }

    /**
     * @param string $what
     *
     * @return CacheItemInterface
     */
    public function get(string $what) : CacheItemInterface
    {
        if (!$this->enabled) {
            return new CacheItem();
        }

        return $this->adapter->getItem($what);
    }

    /**
     * @param CacheItemInterface $what
     * @param bool               $deferred
     *
     * @return bool
     */
    public function set(CacheItemInterface $what, bool $deferred = false) : bool
    {
        if (!$this->enabled) {
            return false;
        }

        $what->expiresAfter(new \DateInterval(sprintf('PT%sS', $this->ttl)));

        if (!$deferred) {
            return $this->adapter->save($what);
        }

        return $this->adapter->saveDeferred($what);
    }

    /**
     * @return bool
     */
    public function commit() : bool
    {
        return $this->adapter->commit();
    }
}
