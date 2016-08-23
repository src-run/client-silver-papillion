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
 * Class MediaVideo.
 */
class MediaVideo extends Media
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
        'embed_html' => [
            'to_property' => 'embeddableIframe'
        ],
        'format' => [
            'to_property' => 'formats',
            'object_fqcn' => MediaFormatVideo::class,
            'object_coll' => true,
        ],
        'source' => [
            'to_property' => 'link'
        ],
        'description' => [
            'to_property' => 'text'
        ],
    ];

    /**
     * @var string
     */
    protected $embeddableIframe;

    /**
     * @return string
     */
    public function getEmbeddableIFrame()
    {
        return $this->embeddableIframe;
    }

    /**
     * @param int $size
     *
     * @return Media
     */
    public function getThumbSmallerThan($size)
    {
        return parent::getFormatSmallerThan($size)->getLink();
    }

    /**
     * @param int $size
     *
     * @return Media
     */
    public function getThumbGreaterThan($size)
    {
        return parent::getFormatGreaterThan($size)->getLink();
    }
}

/* EOF */
