<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Location\Resolver;

use AppBundle\Component\Location\Model\LocationModel;

interface GeoIpResolverInterface
{
    /**
     * Check if IP address information is available
     *
     * @param string $address
     *
     * @return bool
     */
    public function has(string $address) : bool;

    /**
     * Lookup IP address and return location object instance.
     *
     * @param string $address
     *
     * @return LocationModel
     */
    public function lookup(string $address) : LocationModel;

    /**
     * Returns the resolver type as a readable string.
     *
     * @return string
     */
    public function getType() : string;

    /**
     * Returns the priority of the resolver (higher being better).
     *
     * @return int
     */
    public function getPriority() : int;
}

/* EOF */
