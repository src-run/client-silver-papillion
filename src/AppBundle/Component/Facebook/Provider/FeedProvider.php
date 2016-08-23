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

use AppBundle\Component\Facebook\Authentication\AuthenticationInterface;
use AppBundle\Component\Facebook\Model\AbstractModel;
use AppBundle\Component\Facebook\Model\Feed\Feed;
use Facebook\FacebookResponse;
use Psr\Cache\CacheItemInterface;

/**
 * Class FeedProvider.
 */
class FeedProvider extends AbstractProvider
{
    /**
     * @var string
     */
    const ENDPOINT_ROOT = '/%ID%/feed';

    /**
     * @var string[]
     */
    const ENDPOINT_FIELDS = [
        'id',
    ];

    /**
     * @param AuthenticationInterface|null $authentication
     * @param int|null                     $id
     */
    public function __construct(AuthenticationInterface $authentication = null, $id = null)
    {
        parent::__construct($authentication, $authentication->getPageId());
    }

    /**
     * @param FacebookResponse $response
     *
     * @return Feed
     */
    protected function hydrate(FacebookResponse $response)
    {
        return $this->buildPostModel($response->getDecodedBody());
    }

    /**
     * @param mixed[] $data
     *
     * @return Feed
     */
    protected function buildPostModel($data)
    {
        return Feed::create(['posts' => $this->resolvePostsList($data)], $this->getAuthentication()->getPageId());
    }

    /**
     * @param mixed[] $data
     *
     * @return AbstractModel
     */
    protected function resolvePostsList($data)
    {
        if (!$this->isDataList($data)) {
            return null;
        }

        $posts = array_map(function (array $p) {
            try {
                return isset($p['id']) ? $this->resolvePost($p['id']) : null;
            }
            catch (\Exception $e) {
                return null;
            }
        }, $data['data']);

        return array_filter($posts, function ($p) {
            return $p !== null;
        });
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    protected function resolvePost($id)
    {
        $provider = new FeedPostProvider($this->getAuthentication(), $id);
        $provider->setCache($this->getCache());

        return $provider->get();
    }

    /**
     * @return CacheItemInterface
     */
    protected function getCachedItem()
    {
        static $cacheItem;

        if ($cacheItem === null) {
            $cacheItem = $this->getCache()->getItem(sprintf('facebook.feed.%s', md5($this->getEndpoint())));
        }

        return $cacheItem;
    }
}

/* EOF */
