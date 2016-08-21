<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\NodeFactory;

use AppBundle\Component\Facebook\Authentication\AuthenticationInterface;
use AppBundle\Component\Facebook\Model\Feed\Page\PageFeed;
use Facebook\FacebookResponse;

/**
 * Class FeedProvider.
 */
class FeedNode extends AbstractNodeFactory
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
     * @return PageFeed
     */
    protected function hydrate(FacebookResponse $response)
    {
        return $this->hydrateDataList($response);
    }

    /**
     * @return string
     */
    protected function getDataListProviderClass()
    {
        return FeedPostProvider::class;
    }

    /**
     * @param mixed[] $data
     *
     * @return PageFeed
     */
    protected function hydrateModel($data)
    {
        dump($this->createDataList('items', $data));
        die('FUCK');
        return PageFeed::create($this->createDataList('items', $data), $this->getAuthentication()->getPageId());
    }
}

/* EOF */
