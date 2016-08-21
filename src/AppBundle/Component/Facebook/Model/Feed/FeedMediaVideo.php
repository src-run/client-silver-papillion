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
use AppBundle\Component\Facebook\Transformer\MediaStatusTransformer;
use AppBundle\Component\Facebook\Transformer\TransformerInterface;

/**
 * Class FeedMediaVideo.
 */
class FeedMediaVideo extends FeedMedia
{
    /**
     * @var TransformerInterface[]
     */
    const VALUE_TRANSFORMERS = [
        'created_time' => DateTimeTransformer::class,
        'updated_time' => DateTimeTransformer::class,
        'status' => MediaStatusTransformer::class,
    ];

    /**
     * @var array[]
     */
    const MAPPING_DEFINITION = [
        'permalink_url' => [
            'to_property' => 'link',
        ],
        'format' => [
            'to_property' => 'formats',
            'object_fqcn' => FeedMediaVideoFormat::class,
            'object_coll' => true,
        ],
        'source' => [
            'to_property' => 'sourceLink',
        ],
        'created_time' => [
            'to_property' => 'createdOn',
        ],
        'updated_time' => [
            'to_property' => 'updatedOn',
        ],
    ];

    /**
     * @var string
     */
    protected $sourceLink;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var bool
     */
    protected $embeddable;

    /**
     * @return string
     */
    public function getSourceLink()
    {
        return $this->sourceLink;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isEmbeddable()
    {
        return $this->embeddable === true;
    }
}

/* EOF */
