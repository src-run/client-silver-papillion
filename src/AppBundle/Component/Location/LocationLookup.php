<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Location;

use AppBundle\Component\Cache\Cache;
use AppBundle\Component\Location\Model\LocationCollectionModel;
use AppBundle\Component\Location\Model\LocationModel;
use AppBundle\Component\Location\Resolver\LocationResolverInterface;
use SR\Exception\Runtime\RuntimeException;
use SR\Util\Info\ClassInfo;
use Symfony\Component\HttpFoundation\RequestStack;

class LocationLookup implements LocationLookupInterface
{
    /**
     * @var LocationResolverInterface[]
     */
    private $resolvers = [];

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var int
     */
    private $cacheTtl;

    /**
     * @var RequestStack|null
     */
    private $requestStack;

    /**
     * @param LocationResolverInterface[] $resolvers
     * @param Cache|null                  $cache
     * @param int                         $cacheTtl
     */
    public function __construct(array $resolvers = [], Cache $cache = null, int $cacheTtl = 600)
    {
        foreach ($resolvers as $r) {
            $this->addResolver($r);
        }

        $this->cache = $cache;

        if ($this->cache === null) {
            $this->cache = new Cache();
            $this->cache->setEnabled(false);
        }

        $this->cacheTtl = $cacheTtl;
    }

    /**
     * @param RequestStack $requestStack
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Add location resolver implementation instance to lookup chain.
     *
     * @param LocationResolverInterface $resolver
     *
     * @return LocationLookupInterface
     */
    public function addResolver(LocationResolverInterface $resolver) : LocationLookupInterface
    {
        $this->resolvers[] = $resolver;

        return $this;
    }

    /**
     * Check if IP address information is available
     *
     * @param string $address
     *
     * @return bool
     */
    public function has(string $address) : bool
    {
        $resolvers = array_filter($this->resolvers, function (LocationResolverInterface $resolver) use ($address) {
            return $resolver->has($address);
        });

        return count($resolvers) > 0;
    }

    /**
     * Returns the number of resolvers that return valid lookup result.
     *
     * @param string $address
     *
     * @return int
     */
    public function lookupCount(string $address) : int
    {
        return count($this->lookupAll($address));
    }

    /**
     * Lookup IP address and return location object instance.
     *
     * @param string $address
     *
     * @return LocationModel
     */
    public function lookup(string $address) : LocationModel
    {
        $locations = $this->lookupAll($address);

        if ($locations->count() === 0) {
            throw new RuntimeException('Failed "%s" lookup (resolvers: "%s")', $address, implode(',', $this->getRegisteredResolverTypes()));
        }

        return $locations->__toArray()[0];
    }

    /**
     * Lookup IP address and return location object instance.
     *
     * @param string $address
     *
     * @return LocationCollectionModel
     */
    public function lookupAll(string $address) : LocationCollectionModel
    {
        $item = $this->cache->get($this->getCacheKey($address));

        if (!$item->isHit()) {
            $locations = array_map(function (LocationResolverInterface $resolver) use ($address) {
                return $resolver->lookup($address);
            }, array_filter($this->resolvers, function (LocationResolverInterface $resolver) use ($address) {
                return $resolver->has($address);
            }));

            $item->set(new LocationCollectionModel($locations));
            $item->expiresAfter(new \DateInterval(sprintf('PT%dS', $this->cacheTtl)));

            $this->cache->set($item);
        }

        return $item->get();
    }

    /**
     * @return LocationCollectionModel
     */
    public function lookupUsingClientIp() : LocationCollectionModel
    {
        if (!$this->requestStack) {
            throw new RuntimeException('Request stack not provided to location lookup instance!');
        }

        return $this->lookupAll($this->requestStack->getMasterRequest()->getClientIp());
    }

    /**
     * Get an array of the registered resolver types.
     *
     * @return string[]
     */
    public function getRegisteredResolverTypes() : array
    {
        return array_map(function(LocationResolverInterface $r) {
            return $r->getType();
        }, $this->resolvers);
    }

    /**
     * Return name of resolver as string.
     *
     * @return string
     */
    public function getType() : string
    {
        return ClassInfo::getNameShort(static::class);
    }

    /**
     * Returns the priority of the resolver (higher being better).
     *
     * @return int
     */
    public function getPriority() : int
    {
        return -1;
    }

    /**
     * @param string $address
     *
     * @return string
     */
    private function getCacheKey(string $address) : string
    {
        return sprintf('location_lookup_%s_%s', $address, md5($address));
    }
}

/* EOF */
