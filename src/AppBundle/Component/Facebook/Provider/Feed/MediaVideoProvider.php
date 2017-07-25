<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Provider\Feed;

use AppBundle\Component\Facebook\Provider\AbstractProvider;
use Facebook\FacebookResponse;

/**
 * Class MediaVideoProvider.
 */
class MediaVideoProvider extends AbstractProvider
{
    const ENDPOINT_FIELDS = [
        'id',
        'updated_time',
        'created_time',
        'permalink_url',
        'source',
        'format',
        'embeddable',
        'status',
        'title',
        'description',
        'embed_html',
    ];

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
