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
use AppBundle\Component\Facebook\Transformer\EmbedHtmlTransformer;
use AppBundle\Component\Facebook\Transformer\TransformerInterface;

/**
 * Class FeedMediaFormat.
 */
class FeedMediaVideoFormat extends AbstractModel
{
    /**
     * @var TransformerInterface[]
     */
    const VALUE_TRANSFORMERS = [
        'embed_html' => EmbedHtmlTransformer::class
    ];

    /**
     * @var array[]
     */
    const MAPPING_DEFINITION = [
        'picture' => [
            'to_property' => 'thumbnailLink'
        ],
        'embed_html' => [
            'to_property' => 'embed'
        ],
    ];

    /**
     * @var string[]
     */
    protected $embed;

    /**
     * @var string
     */
    protected $filter;

    /**
     * @var string
     */
    protected $thumbnailLink;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var int
     */
    protected $width;

    protected function assignDataToModel()
    {
        foreach ($this->data as $key => $data) {
            $this->assignFieldToModel($data, $key);
        }

        return $this;
    }

    /**
     * @return \string[]
     */
    public function getEmbed()
    {
        return $this->embed;
    }

    /**
     * @return null|string
     */
    public function getEmbedUrl()
    {
        return isset($this->embed['url']) ? $this->embed['url'] : null;
    }

    /**
     * @return null|string
     */
    public function getEmbedHtml()
    {
        return isset($this->embed['html']) ? $this->embed['html'] : null;
    }

    /**
     * @return string
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return int
     */
    public function getFilterSize()
    {
        if ($this->isFilterNative()) {
            return $this->height > $this->width ? $this->height : $this->width;
        }

        return explode('x', $this->filter)[0];
    }

    /**
     * @return bool
     */
    public function isFilterNative()
    {
        return $this->filter === 'native';
    }

    /**
     * @return string
     */
    public function getThumbnailLink()
    {
        return $this->thumbnailLink;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }
}

/* EOF */
