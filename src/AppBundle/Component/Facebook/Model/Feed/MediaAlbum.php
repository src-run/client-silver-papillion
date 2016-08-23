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

use AppBundle\Component\Facebook\Transformer\DateTimeTransformer;

/**
 * Class MediaAlbum.
 */
class MediaAlbum extends Media
{
    /**
     * @var TransformerInterface[]
     */
    const VALUE_TRANSFORMERS = [
        'created_time' => DateTimeTransformer::class,
        'updated_time' => DateTimeTransformer::class,
    ];

    /**
     * @var array[]
     */
    const MAPPING_DEFINITION = [
        'created_time' => [
            'to_property' => 'createdOn',
        ],
        'updated_time' => [
            'to_property' => 'updatedOn',
        ],
    ];

    /**
     * @var MediaPhoto[]
     */
    protected $photos;

    /**
     * @return bool
     */
    public function hasPhotos()
    {
        return count($this->photos) > 0;
    }

    /**
     * @return MediaPhoto[]
     */
    public function getPhotos()
    {
        return $this->photos;
    }
}

/* EOF */
