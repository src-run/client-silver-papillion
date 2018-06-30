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

use AppBundle\Component\Location\Model\Field\CollectionField;
use AppBundle\Component\Location\Model\Field\FieldInterface;
use AppBundle\Component\Location\Model\Field\LocalizedField;
use AppBundle\Component\Location\Model\Field\NullField;
use AppBundle\Component\Location\Model\Field\ScalarField;
use AppBundle\Component\Location\Model\LocationModel;
use SR\Exception\Logic\InvalidArgumentException;
use SR\Utilities\ClassQuery;

abstract class AbstractGeoIpResolver implements GeoIpResolverInterface
{
    /**
     * @var string
     */
    protected $database;

    /**
     * @param string $database
     */
    public function __construct(string $database)
    {
        $this->database = $database;
    }

    /**
     * Check if IP address information is available
     *
     * @param string $address
     *
     * @return bool
     */
    final public function has(string $address) : bool
    {
        return $this->hasResultFor($address);
    }

    /**
     * Lookup IP address and return location object instance.
     *
     * @param string $address
     *
     * @return LocationModel
     */
    final public function lookup(string $address) : LocationModel
    {
        return $this->getResultFor($address);
    }

    /**
     * @return string
     */
    final public function getType() : string
    {
        return ClassQuery::getNameShort(static::class);
    }

    /**
     * @return int
     */
    public function getPriority() : int
    {
        return -1;
    }

    /**
     * @param FieldInterface[]|NullField[]|CollectionField[]|ScalarField[]|LocalizedField[] $data
     *
     * @return LocationModel
     */
    final protected function createValidLocationModel(array $data) : LocationModel
    {
        return new LocationModel($data, true, $this->getPriority());
    }

    /**
     * @return LocationModel
     */
    final protected function createInvalidLocationModel() : LocationModel
    {
        return new LocationModel([], false, $this->getPriority());
    }

    /**
     * @param string $address
     *
     * @return bool
     */
    protected function hasResultFor(string $address): bool
    {
        try {
            $this->getFieldModels($address);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        return true;
    }

    /**
     * @param string $address
     *
     * @return LocationModel
     */
    protected function getResultFor(string $address): LocationModel
    {
        try {
            return $this->createValidLocationModel($this->getFieldModels($address));
        } catch (InvalidArgumentException $e) {
            return $this->createInvalidLocationModel();
        }
    }

    /**
     * @return mixed
     */
    abstract protected function getDatabaseInstance();

    /**
     * @return string[]
     */
    abstract protected static function getFieldMappings(): array;

    /**
     * @param string $address
     *
     * @throws InvalidArgumentException On failed lookup
     *
     * @return mixed[]
     */
    abstract protected function getFieldModels(string $address) : array;
}

/* EOF */
