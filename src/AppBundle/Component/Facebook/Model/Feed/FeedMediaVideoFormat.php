<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Model\Feed;

use AppBundle\Component\Facebook\Model\AbstractModel;

/**
 * Class FeedMediaFormat.
 */
class FeedMediaFormat extends AbstractModel
{
    /**
     * @var array[]
     */
    const MAPPING_DEFINITION = [
        'embed_html' => [
            'to_property' => 'embeddableHtmlLink'
        ],
        'filter' => [
            'to_property' => 'sizeRestriction'
        ],
        'picture' => [
            'to_property' => 'imageLink'
        ],
    ];

    /**
     * @var string
     */
    protected $embeddableHtmlLink;

    /**
     * @var string
     */
    protected $sizeRestriction;

    /**
     * @var string
     */
    protected $imageLink;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var int
     */
    protected $width;
}

/* EOF */
