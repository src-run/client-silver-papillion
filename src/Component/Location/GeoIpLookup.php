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
use AppBundle\Component\Location\Resolver\GeoIpResolverInterface;
use SR\Exception\Runtime\RuntimeException;
use SR\Utilities\ClassQuery;
use Symfony\Component\HttpFoundation\RequestStack;

class GeoIpLookup implements GeoIpLookupInterface
{
    /**
     * @var GeoIpResolverInterface[]
     */
    private $resolvers = [];

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var RequestStack|null
     */
    private $requestStack;

    /**
     * @param GeoIpResolverInterface[] $resolvers
     * @param Cache|null                  $cache
     */
    public function __construct(array $resolvers = [], Cache $cache = null)
    {
        foreach ($resolvers as $r) {
            $this->addResolver($r);
        }

        if ($cache === null) {
            $cache = new Cache();
            $cache->setEnabled(false);
        }

        $this->cache = $cache;
    }

    /**
     * Add location resolver implementation instance to lookup chain.
     *
     * @param GeoIpResolverInterface $resolver
     *
     * @return GeoIpLookupInterface
     */
    public function addResolver(GeoIpResolverInterface $resolver) : GeoIpLookupInterface
    {
        $this->resolvers[] = $resolver;

        return $this;
    }

    /**
     * @param RequestStack $requestStack
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
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
        return count(array_filter($this->resolvers, function (GeoIpResolverInterface $resolver) use ($address) {
            return $resolver->has($address);
        })) > 0;
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
            $locations = array_map(function (GeoIpResolverInterface $resolver) use ($address) {
                return $resolver->lookup($address);
            }, array_filter($this->resolvers, function (GeoIpResolverInterface $resolver) use ($address) {
                return $resolver->has($address);
            }));

            $item->set(new LocationCollectionModel($locations));
            $item->expiresAfter(new \DateInterval('PT600S'));

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
        return array_map(function(GeoIpResolverInterface $r) {
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
        return ClassQuery::getNameShort(static::class);
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
        return preg_replace('{[^a-zA-Z0-9_-]+}', '-', sprintf('location_lookup_%s_%s', $address, md5($address)));
    }
}
