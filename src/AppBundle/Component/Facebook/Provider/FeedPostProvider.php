<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Provider;

use AppBundle\Component\Facebook\Exception\FacebookException;
use AppBundle\Component\Facebook\Model\AbstractModel;
use AppBundle\Component\Facebook\Model\Feed\Media;
use AppBundle\Component\Facebook\Model\Feed\MediaAlbum;
use Facebook\FacebookResponse;
use Symfony\Component\Cache\CacheItem;

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
        'reactions',
    ];

    /**
     * @param FacebookResponse $response
     *
     * @return mixed[]
     */
    protected function hydrate(FacebookResponse $response)
    {
        return $this->buildAttachments($response->getDecodedBody());
    }

    /**
     * @param mixed[] $data
     *
     * @return array
     */
    protected function buildAttachments(array $data)
    {
        if (!isset($data['attachments']) || !isset($data['attachments']['data'])) {
            return $data;
        }

        $data['attachments']['data'] = $attachments = array_map(function ($a) {
            return $this->resolveAttachment($a);
        }, $data['attachments']['data']);

        $data['attachments']['data'] = array_filter($data['attachments']['data'], function ($p) {
            return $p !== null;
        });

        $data['attachments']['data'] = $this->flattenAttachments($data['attachments']['data']);

        return $data;
    }

    /**
     * @param Media[] $attachments
     *
     * @return Media[]
     */
    protected function flattenAttachments($attachments)
    {
        $flat = [];

        foreach ($attachments as $a) {
            if ($a instanceof MediaAlbum) {
                $flat = array_merge($flat, $a->getPhotos());
            } else {
                $flat[] = $a;
            }
        }

        return $flat;
    }

    /**
     * @param mixed[] $data
     *
     * @throws FacebookException
     *
     * @return mixed
     */
    protected function resolveAttachment($data)
    {
        if (!isset($data['target']) || !isset($data['target']['id'])) {
            throw new FacebookException('Could not find target ID of passed data.');
        }

        $type = $this->determineDataType($data);
        $model = $this->instantiateDataModel($type);

        if ($type === 'album') {
            $photos = array_map(function ($p) {
                return $this->resolveAttachment($p);
            }, (isset($data['subattachments']) && isset($data['subattachments']['data'])) ? $data['subattachments']['data'] : []);

            $model->hydrate(['photos' => $photos]);
        } else {
            $provider = $this->instantiateDataProvider($type, $data['target']['id']);
            $model->hydrate($provider->get());
        }

        return $model;
    }

    protected function resolvePhoto($data)
    {
        return $data;
    }

    /**
     * @param string $type
     *
     * @return AbstractModel
     */
    protected function instantiateDataModel(string $type) : AbstractModel
    {
        $fqcn = implode('\\', array_merge(array_slice(explode('\\', __NAMESPACE__), 0, -1), ['Model', 'Feed', 'Media'.ucfirst($type)]));

        return $this->instantiateFqcn($fqcn);
    }

    /**
     * @param string $type
     *
     * @return ProviderInterface
     */
    protected function instantiateDataProvider(string $type, string $id) : ProviderInterface
    {
        $fqcn = implode('\\', array_merge([__NAMESPACE__], ['Feed', 'Media'.ucfirst($type).'Provider']));

        $provider = $this->instantiateFqcn($fqcn, $this->getAuthentication(), $id);
        $provider->setCache($this->getCache());

        return $provider;
    }

    /**
     * @param string $fqcn
     * @param array ...$with
     *
     * @return ProviderInterface
     *
     * @throws \SR\Exception\ExceptionInterface
     */
    protected function instantiateFqcn(string $fqcn, ...$with)
    {
        $reflection = new \ReflectionClass($fqcn);

        if (!$reflection->isInstantiable()) {
            throw FacebookException::create()
                ->setMessage('Class %s is not instantiable.', $fqcn);
        }

        return $reflection->newInstanceArgs($with);
    }

    /**
     * @param mixed[] $data
     *
     * @throws FacebookException
     *
     * @return string
     */
    protected function determineDataType($data)
    {
        if (!isset($data['type'])) {
            throw new FacebookException('Could not find type in passed data array.');
        }

        return substr($data['type'], 0, 5);
    }

    /**
     * @return CacheItem
     */
    protected function getCachedItem()
    {
        return new CacheItem();
    }
}

/* EOF */
