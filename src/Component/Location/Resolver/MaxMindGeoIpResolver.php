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
use MaxMind\Db\Reader;
use SR\Exception\Logic\InvalidArgumentException;
use SR\Utilities\ArrayQuery;

class MaxMindGeoIpResolver extends AbstractGeoIpResolver
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
     * @return FieldInterface[]|NullField[]|CollectionField[]|ScalarField[]|LocalizedField[]
     */
    protected function getFieldModels(string $address) : array
    {
        try {
            $models = $this->mapResults($this->getDatabaseInstance()->get($address) ?: [], $address);
        } catch (\Exception $exception) {
            throw new InvalidArgumentException('Unable to lookup "%s" address using "%s(%s)"', $address, $this->getType(), $this->database, $exception);
        }

        if (!is_array($models) || 0 === count($models)) {
            throw new InvalidArgumentException('Unable to lookup "%s" address using "%s(%s)"', $address, $this->getType(), $this->database);
        }

        return $models;
    }

    /**
     * @return string[]
     */
    protected static function getFieldMappings(): array
    {
        return [
            'zipCode' => 'postal.code',
            'latitude' => 'location.latitude',
            'longitude' => 'location.longitude',
            'metroCode' => 'location.metro_code',
            'timeZone' => 'location.time_zone',
            'accuracyRadias' => 'location.accuracy_radius',
            'city' => 'city',
            'continent' => 'continent',
            'country' => 'country',
            'registeredCountry' => 'registered_country',
            'regions' => 'subdivisions',
        ];
    }

    /**
     * @param string $name
     * @param array  $data
     *
     * @return mixed|null
     */
    private function resolveFieldValue(string $name, array $data)
    {
        $index = explode('.', $name);
        $value = [$data];
        $final = null;

        foreach ($index as $i => $k) {
            if (array_key_exists($k, $value[$i])) {
                $value[$i + 1] = $value[$i][$k];
            }
        }

        return count($index) === count($value) - 1 ? array_pop($value) : null;
    }

    /**
     * @param mixed $value
     *
     * @return FieldInterface
     */
    private function hydrateField($value = null): FieldInterface
    {
        if (null !== $value) {
            if (is_scalar($value)) {
                return new ScalarField($value);
            }

            if (!ArrayQuery::isAssociative($value)) {
                return $this->hydrateFieldCollection($value);
            }

            if (isset($value['names']) && is_array($value['names'])) {
                return $this->hydrateLocalizedField($value);
            }
        }

        return new NullField();
    }

    /**
     * @param array $collection
     *
     * @return CollectionField
     */
    private function hydrateFieldCollection(array $collection): CollectionField
    {
        $hydrated = array_map(function ($value) {
            return $this->hydrateField($value);
        }, $collection);

        return new CollectionField(...$hydrated);
    }

    /**
     * @param array $data
     *
     * @return LocalizedField
     */
    private function hydrateLocalizedField(array $data): LocalizedField
    {
        $id = null;
        $iso = null;
        $val = [];

        foreach ($data as $i => $v) {
            if (false !== strpos($i, 'id')) {
                $id = $v;
            } elseif (false !== strpos($i, 'code')) {
                $iso = $v;
            } elseif (false !== strpos($i, 'names') && is_array($v)) {
                $val = $v;
            }
        }

        return new LocalizedField($id, $iso, $val);
    }

    /**
     * @param array  $data
     * @param string $address
     *
     * @return array
     */
    private function mapResults(array $data, string $address): array
    {
        $fields = array_map(function (string $field) use ($data) {
            return $this->hydrateField($this->resolveFieldValue($field, $data));
        }, static::getFieldMappings());

        return array_merge([
            'resolver' => new ScalarField($this->getType()),
            'ipVersion' => new ScalarField(4),
            'ipAddress' => new ScalarField($address)
        ], $fields);
    }
}

/* EOF */
