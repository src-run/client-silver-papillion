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
use AppBundle\Component\Facebook\Model\Feed\Page\PageFeed;
use Facebook\FacebookResponse;

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
     * @param AuthenticationInterface $authentication
     */
    public function __construct(AuthenticationInterface $authentication = null)
    {
        parent::__construct($authentication);

        $this->setId($this->getAuthentication()->getPageId());
    }

    /**
     * @param FacebookResponse $response
     *
     * @return PageFeed
     */
    protected function hydrate(FacebookResponse $response)
    {
        $data = $response->getDecodedBody();

        if (!isset($data['data'])) {
            return null;
        }

        $data['data'] = array_map(function ($d) {
            if (!isset($d['id'])) {
                return null;
            }

            $p = new FeedPostProvider($d['id'], $this->getAuthentication());
            $p->setCache($this->getCache());
            $p->setAuthentication($this->getAuthentication());

            return $p->get();
        }, $data['data']);

        $data['data'] = array_filter($data['data'], function ($d) {
            return $d !== null;
        });

        return PageFeed::create(['items' => $data], $this->getAuthentication()->getPageId());
    }
}

/* EOF */
