<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Location\Model;

final class LocationModel
{
    /**
     * @var mixed[]
     */
    private $data = [];

    /**
     * @var bool
     */
    private $valid;

    /**
     * @var int
     */
    private $priority;

    /**
     * @param mixed[] $data
     * @param int     $priority
     */
    public function __construct(array $data = [], bool $valid = true, int $priority = -1)
    {
        $this->data = $data;
        $this->valid = $valid;
        $this->priority = $priority;
    }

    /**
     * @return mixed[]
     */
    public function __toArray() : array
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function hasData() : bool
    {
        return count($this->data) > 0;
    }

    /**
     * @return bool
     */
    public function isValid() : bool
    {
        return $this->valid;
    }

    /**
     * @return int
     */
    public function getPriority() : int
    {
        return $this->priority;
    }

    /**
     * @return mixed|null
     */
    public function getResolver()
    {
        return $this->getDataIndex('resolver');
    }

    /**
     * @return mixed|null
     */
    public function getIpVersion()
    {
        return $this->getDataIndex('ipVersion');
    }

    /**
     * @return mixed|null
     */
    public function getIpAddress()
    {
        return $this->getDataIndex('ipAddress');
    }

    /**
     * @return mixed|null
     */
    public function getLatitude()
    {
        return $this->getDataIndex('latitude');
    }

    /**
     * @return mixed|null
     */
    public function getLongitude()
    {
        return $this->getDataIndex('longitude');
    }

    /**
     * @return mixed|null
     */
    public function getZipCode()
    {
        return $this->getDataIndex('zipCode');
    }

    /**
     * @return mixed|null
     */
    public function getRegionCode()
    {
        return $this->getDataIndex('regionCode');
    }

    /**
     * @return mixed|null
     */
    public function getRegionName()
    {
        return $this->getDataIndex('regionName');
    }

    /**
     * @return mixed|null
     */
    public function getCityName()
    {
        return $this->getDataIndex('cityName');
    }

    /**
     * @return mixed|null
     */
    public function getCountryCode()
    {
        return $this->getDataIndex('countryCode');
    }

    /**
     * @return mixed|null
     */
    public function getCountryName()
    {
        return $this->getDataIndex('countryName');
    }

    /**
     * @return mixed|null
     */
    public function getTimeZone()
    {
        return $this->getDataIndex('timeZone');
    }

    /**
     * @param string $index
     *
     * @return mixed|null
     */
    private function getDataIndex(string $index)
    {
        if (isset($this->data[$index])) {
            return $this->data[$index];
        }

        return null;
    }
}

/* EOF */
