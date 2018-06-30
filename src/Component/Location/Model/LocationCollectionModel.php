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

use SR\Reflection\Exception\InvalidArgumentException;
use SR\Reflection\Inspect;

final class LocationCollectionModel implements \Countable, \IteratorAggregate
{
    /**
     * @var LocationModel[]
     */
    private $locations = [];

    /**
     * @param LocationModel[] $locations
     */
    public function __construct(array $locations = [])
    {
        $this->locations = $locations;
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed|null
     */
    public function __call(string $method, array $arguments = [])
    {
        if (substr($method, 0, 3) === 'has') {
            return $this->callDynamicHasMethod($method);
        }

        return $this->callDynamicGetMethod($method);
    }

    /**
     * @param string $method
     *
     * @return bool
     */
    private function callDynamicHasMethod(string $method) : bool
    {
        $property = lcfirst(substr($method, 3));

        foreach ($this->locations as $l) {
            try {
                $r = Inspect::useInstance($l);
                $p = $r->getProperty('data');
                $v = $p->value($l);

                if (isset($v[$property]) && null !== $v[$property]) {
                    return true;
                }
            } catch (InvalidArgumentException $e) {}
        }

        return false;
    }

    /**
     * @param string $method
     *
     * @return mixed|null
     */
    private function callDynamicGetMethod(string $method)
    {
        foreach ($this->locations as $l) {
            if (is_callable([$l, $method]) && null !== $result = call_user_func([$l, $method])) {
                return $result;
            }
        }

        return null;
    }

    /**
     * @return LocationModel[]
     */
    public function __toArray() : array
    {
        $locations = $this->locations;

        usort($locations, function (LocationModel $a, LocationModel $b) {
            return $a->getPriority() < $b->getPriority();
        });

        return $locations;
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return count($this->locations);
    }

    /**
     * @return \ArrayIterator|LocationModel[]
     */
    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator($this->__toArray());
    }
}

/* EOF */
