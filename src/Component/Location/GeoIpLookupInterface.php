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
use AppBundle\Component\Location\Resolver\GeoIpResolverInterface;

interface GeoIpLookupInterface extends GeoIpResolverInterface
{
    /**
     * @param GeoIpResolverInterface[] $resolvers
     * @param Cache|null                  $cache
     */
    public function __construct(array $resolvers = [], Cache $cache = null);

    /**
     * Add location resolver implementation instance to lookup chain.
     *
     * @param GeoIpResolverInterface $resolver
     *
     * @return GeoIpLookupInterface
     */
    public function addResolver(GeoIpResolverInterface $resolver) : GeoIpLookupInterface;

    /**
     * Get an array of the registered resolver types.
     *
     * @return string[]
     */
    public function getRegisteredResolverTypes() : array;

    /**
     * Returns the number of resolvers that return valid lookup result.
     *
     * @param string $address
     *
     * @return int
     */
    public function lookupCount(string $address) : int;

    /**
     * Lookup IP address and return location object instance.
     *
     * @param string $address
     *
     * @return LocationCollectionModel
     */
    public function lookupAll(string $address) : LocationCollectionModel;

    /**
     * @return LocationCollectionModel
     */
    public function lookupUsingClientIp() : LocationCollectionModel;
}
