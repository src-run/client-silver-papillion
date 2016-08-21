<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Provider;

use AppBundle\Component\Facebook\Model\Feed\FeedMediaPhoto;
use AppBundle\Component\Facebook\Model\Feed\FeedMediaVideo;
use Facebook\FacebookResponse;

/**
 * Class FeedPostProvider.
 */
class FeedPostProvider extends AbstractProvider
{
    const ENDPOINT_FIELDS = [
        'id',
        'created_time',
        'updated_time',
        'permalink_url',
        'source',
        'type',
        'properties',
        'is_published',
        'from',
        'icon',
        'message',
        'attachments',
        'comments',
        'reactions'
    ];

    /**
     * @param FacebookResponse $response
     *
     * @return mixed[]
     */
    protected function hydrate(FacebookResponse $response)
    {
        return $this->hydrateDataListProperties(['attachments'], $response->getDecodedBody());
    }

    /**
     * @param string[] $properties
     * @param mixed[]  $data
     *
     * @return mixed[]
     */
    protected function hydrateDataListProperties(array $properties, $data)
    {
        array_walk($data, function (&$value, $index) use ($properties) {
            if (in_array($index, $properties) && $this->isDataList($value)) {
                $value = $this->hydrateDataListForProperty($index, $value);
            }
        });

        return $data;
    }

    /**
     * @param string  $property
     * @param mixed[] $data
     *
     * @return array
     */
    protected function hydrateDataListForProperty($property, array $data)
    {
        $dataList = $data['data'];

        array_walk($dataList, function (&$value, $index) use ($property) {
            $value = $this->resolveDataListItemForProperty($property, $index, $value);
        });

        return [
            $dataList
        ];
    }

    /**
     * @param string  $property
     * @param string  $index
     * @param mixed[] $value
     *
     * @return mixed
     */
    protected function resolveDataListItemForProperty($property, $index, $value)
    {
        if (!isset($value['target']) || !isset($value['target']['id']) || !isset($value['type'])) {
            return null;
        }

        $type = substr($value['type'], 0, 5);
        $fqcn = __NAMESPACE__.'\Feed'.ucfirst($property).ucfirst($type).'Provider';

        if (!class_exists($fqcn)) {
            return null;
        }

        $reflection = new \ReflectionClass($fqcn);
        $provider = $reflection->newInstanceArgs([$this->getAuthentication(), $value['target']['id']]);
        $provider->setCache($this->getCache());

        return $this->hydrateDataListItemModel($type, $provider->get());
    }

    /**
     * @param string  $type
     * @param mixed[] $data
     *
     * @return null|FeedMediaVideo|FeedMediaPhoto
     */
    protected function hydrateDataListItemModel($type, $data)
    {
        if ($type === 'video') {
            return FeedMediaVideo::create($data);
        }

        if ($type === 'photo') {
            return FeedMediaPhoto::create($data);
        }

        return null;
    }
}

/* EOF */
