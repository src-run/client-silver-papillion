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
use SR\Exception\Logic\InvalidArgumentException;
use SR\Util\Info\ClassInfo;

abstract class AbstractLocationResolver implements LocationResolverInterface
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
        return ClassInfo::getNameShort(static::class);
    }

    /**
     * @return int
     */
    public function getPriority() : int
    {
        return -1;
    }

    /**
     * @param array $data
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
            $this->getDatabaseResult($address);
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
            $result = $this->getDatabaseResult($address);

            uksort($result, function ($a, $b) {
                return $a > $b;
            });

            return $this->createValidLocationModel($result);

        } catch (InvalidArgumentException $e) {

            return $this->createInvalidLocationModel();
        }
    }

    /**
     * @return mixed
     */
    abstract protected function getDatabaseInstance();

    /**
     * @param string $address
     *
     * @throws InvalidArgumentException On failed lookup
     *
     * @return mixed[]
     */
    abstract protected function getDatabaseResult(string $address) : array;
}

/* EOF */
