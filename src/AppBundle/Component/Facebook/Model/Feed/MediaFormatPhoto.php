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

/**
 * Class MediaFormatPhoto.
 */
class MediaFormatPhoto extends MediaFormat
{
    /**
     * @var array[]
     */
    const MAPPING_DEFINITION = [
        'source' => [
            'to_property' => 'link'
        ],
    ];
}

/* EOF */
