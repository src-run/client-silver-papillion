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

use AppBundle\Component\Location\Model\Field\CollectionField;
use AppBundle\Component\Location\Model\Field\FieldInterface;
use AppBundle\Component\Location\Model\Field\LocalizedField;
use AppBundle\Component\Location\Model\Field\NullField;
use AppBundle\Component\Location\Model\Field\ScalarField;

final class LocationModel
{
    /**
     * @var mixed[]
     */
    private $fields = [];

    /**
     * @var bool
     */
    private $valid;

    /**
     * @var int
     */
    private $priority;

    /**
     * @param FieldInterface[]|NullField[]|CollectionField[]|ScalarField[]|LocalizedField[] $fields
     * @param bool                                                                          $valid
     * @param int                                                                           $priority
     */
    public function __construct(array $fields = [], bool $valid = true, int $priority = -1)
    {
        $this->fields = $fields;
        $this->valid = $valid;
        $this->priority = $priority;
    }

    /**
     * @return mixed[]
     */
    public function __toArray() : array
    {
        return array_map(function (FieldInterface $field) {
            return $field->getValue();
        }, $this->fields);
    }

    /**
     * @return bool
     */
    public function hasData() : bool
    {
        return 0 !== count(array_filter($this->fields, function (FieldInterface $field) {
            return $field->hasValue();
        }));
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
        return $this->getField('resolver')->getValue();
    }

    /**
     * @return mixed|null
     */
    public function getIpVersion()
    {
        return $this->getField('ipVersion')->getValue();
    }

    /**
     * @return mixed|null
     */
    public function getIpAddress()
    {
        return $this->getField('ipAddress')->getValue();
    }

    /**
     * @return mixed|null
     */
    public function getLatitude()
    {
        return $this->getField('latitude')->getValue();
    }

    /**
     * @return mixed|null
     */
    public function getLongitude()
    {
        return $this->getField('longitude')->getValue();
    }

    /**
     * @return mixed|null
     */
    public function getZipCode()
    {
        return $this->getField('zipCode')->getValue();
    }

    /**
     * @return LocalizedField
     */
    public function getRegion(): LocalizedField
    {
        return $this->getField('regions')->getField() ?? new LocalizedField();
    }

    /**
     * @return string|null
     */
    public function getRegionId()
    {
        return $this->getRegion()->getId();
    }

    /**
     * @return string|null
     */
    public function getRegionCode()
    {
        return $this->getRegion()->getCode();
    }

    /**
     * @return string|null
     */
    public function getRegionName()
    {
        return $this->getRegion()->getValue();
    }

    /**
     * @return LocalizedField
     */
    public function getCity(): LocalizedField
    {
        return $this->getField('city') ?? new LocalizedField();
    }

    /**
     * @return string|null
     */
    public function getCityId()
    {
        return $this->getCity()->getId();
    }

    /**
     * @return string|null
     */
    public function getCityCode()
    {
        return $this->getCity()->getCode();
    }

    /**
     * @return string|null
     */
    public function getCityName()
    {
        return $this->getCity()->getValue();
    }

    /**
     * @return LocalizedField
     */
    public function getCountry(): LocalizedField
    {
        return $this->getField('country') ?? new LocalizedField();
    }

    /**
     * @return string|null
     */
    public function getCountryId()
    {
        return $this->getCountry()->getId();
    }

    /**
     * @return string|null
     */
    public function getCountryCode()
    {
        return $this->getCountry()->getCode();
    }

    /**
     * @return string|null
     */
    public function getCountryName()
    {
        return $this->getCountry()->getValue();
    }

    /**
     * @return mixed|null
     */
    public function getTimeZone()
    {
        return $this->getField('timeZone')->getValue();
    }

    /**
     * @param string $key
     *
     * @return FieldInterface|NullField|CollectionField|ScalarField|LocalizedField
     */
    public function getField(string $key): FieldInterface
    {
        if (array_key_exists($key, $this->fields)) {
            return $this->fields[$key];
        }

        return new NullField();
    }
}

/* EOF */
