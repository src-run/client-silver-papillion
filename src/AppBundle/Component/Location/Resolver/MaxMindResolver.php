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

use MaxMind\Db\Reader;
use SR\Exception\Logic\InvalidArgumentException;

class MaxMindResolver extends AbstractLocationResolver
{
    /**
     * @return int
     */
    public function getPriority() : int
    {
        return 10;
    }

    /**
     * @return Reader
     */
    protected function getDatabaseInstance() : Reader
    {
        static $instance;

        if (!$instance instanceof Reader) {
            $instance = new Reader($this->database);
        }

        return $instance;
    }

    /**
     * @param string $address
     *
     * @throws \MaxMind\Db\Reader\InvalidDatabaseException On failed lookup
     *
     * @return mixed[]
     */
    protected function getDatabaseResult(string $address) : array
    {
        static $results = [];

        if (!isset($results[$address])) {
            $results[$address] = $results[$address] = $this->mapResults(
                $this->getDatabaseInstance()->get($address) ?: [], $address
            );
        }

        if (!is_array($results[$address]) || count($results[$address]) === 0) {
            throw new InvalidArgumentException('No result for "%s" using "%s(%s)"', $address, $this->getType(), $this->database);
        }


        return $results[$address];
    }

    /**
     * @param array  $result
     * @param string $address
     *
     * @return array
     */
    private function mapResults(array $result, string $address): array
    {
        $normalized = [
            'resolver' => $this->getType(),
            'ipVersion' => 4,
            'ipAddress' => $address,
            'latitude' => null,
            'longitude' => null,
            'countryCode' => null,
            'countryName' => null,
            'zipCode' => null,
            'cityName' => null,
            'regionName' => null,
            'regionCode' => null,
            'timeZone' => null,
        ];

        if (isset($result['postal']) && isset($result['postal']['code'])) {
            $normalized['zipCode'] = $result['postal']['code'];
        }

        if (isset($result['subdivisions']) && isset($result['subdivisions'][0]) && isset($result['subdivisions'][0]['iso_code'])) {
            $normalized['regionCode'] = $result['subdivisions'][0]['iso_code'];
            $normalized['regionName'] = $result['subdivisions'][0]['names']['en'];
        }

        if (isset($result['city']) && isset($result['city']['names'])) {
            $normalized['cityName'] = $result['city']['names']['en'];
        }

        if (isset($result['country']) && isset($result['country']['iso_code'])) {
            $normalized['countryCode'] = $result['country']['iso_code'];
            $normalized['countryName'] = $result['country']['names']['en'];
        }

        if (isset($result['location']) && isset($result['location']['latitude'])) {
            $normalized['latitude'] = $result['location']['latitude'];
            $normalized['longitude'] = $result['location']['longitude'];
            $normalized['timeZone'] = $result['location']['time_zone'];
        }

        $normalized['ipVersion'] = 4;

        return $normalized;
    }
}

/* EOF */
