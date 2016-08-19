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
 * Class FeedPostProvider.
 */
class FeedPostProvider extends AbstractProvider
{
    const ENDPOINT_FIELDS = [
        'id',
        'permalink_url',
        'created_time',
        'is_published',
        'message',
        'caption',
        'description',
        'from',
        'icon',
        'link',
        'name',
        'picture',
        'source',
        'status_type',
        'type',
        'sharedposts',
        'story',
        'attachments',
        'comments',
        'reactions'
    ];

    /**
     * @param string                  $id
     * @param AuthenticationInterface $authentication
     */
    public function __construct($id, AuthenticationInterface $authentication = null)
    {
        parent::__construct($authentication);

        $this->setId($id);
    }

    /**
     * @param FacebookResponse $response
     *
     * @return mixed[]
     */
    protected function hydrate(FacebookResponse $response)
    {
        return $response->getDecodedBody();
    }
}

/* EOF */
