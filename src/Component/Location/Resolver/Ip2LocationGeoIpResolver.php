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

use IP2Location\Database;
use SR\Exception\Logic\InvalidArgumentException;

class Ip2LocationGeoIpResolver extends AbstractGeoIpResolver
{
    /**
     * @var bool
     */
    protected $cache;

    /**
     * @param $database
     */
    public function __construct(string $database, bool $cache = false)
    {
        parent::__construct($database);

        $this->cache = $cache;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return 20;
    }

    /**
     * @return Database
     */
    protected function getDatabaseInstance(): Database
    {
        static $instance;

        if (!$instance instanceof Database) {
            $instance = new Database($this->database, $this->cache ? Database::MEMORY_CACHE : Database::FILE_IO);
        }

        return $instance;
    }

    /**
     * @param string $address
     *
     * @throws \InvalidArgumentException On failed lookup
     *
     * @return mixed[]
     */
    protected function getFieldModels(string $address): array
    {
        static $results = [];

        if (!isset($results[$address])) {
            $results[$address] = $this->mapResults(
                $this->getDatabaseInstance()->lookup($address, Database::ALL) ?: []
            );
        }

        if (!is_array($results[$address]) || count($results[$address]) === 0) {
            throw new InvalidArgumentException('No result for "%s" using "%s(%s)"', $address, $this->getType(), $this->database);
        }

        return $results[$address];
    }

    /**
     * @return string[]
     */
    protected static function getFieldMappings(): array
    {
        return [
            'zipCode' => 'zipCode',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'metroCode' => null,
            'timeZone' => 'timeZone',
            'accuracyRadias' => null,
            'city' => 'city',
            'continent' => 'continent',
            'country' => 'country',
            'registeredCountry' => 'registered_country',
            'regions' => 'subdivisions',
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function mapResults(array $data): array
    {
        return [];
        $fields = array_map(function (string $field) use ($data) {
            return $this->hydrateField($this->resolveFieldValue($field, $data));
        }, static::getFieldMappings());

        return array_merge([
            'resolver' => new ScalarField($this->getType()),
            'ipVersion' => new ScalarField(4),
            'ipAddress' => new ScalarField($address)
        ], $fields);
        $normalized = [
            'resolver' => $this->getType(),
            'ipVersion' => null,
            'ipAddress' => null,
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

        foreach (['ipVersion', 'ipAddress', 'latitude', 'longitude', 'countryCode', 'countryName', 'zipCode', 'cityName', 'regionName', 'timeZone'] as $key) {
            $normalized[$key] = isset($data[$key]) && $data[$key] !== '-' && $data[$key] !== 0.0 ? $data[$key] : null;
        }

        return $normalized;
    }
}

/* EOF */
