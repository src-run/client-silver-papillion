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
 * Class FeedMedia.
 */
abstract class MediaFormat extends AbstractModel
{
    /**
     * @var Media
     */
    protected $mediaReference;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var string
     */
    protected $width;

    /**
     * @return Media
     */
    public function getMediaParent()
    {
        return $this->mediaReference;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width !== 0 ? $this->width : 1280;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height !== 0 ? $this->height : 720;
    }

    /**
     * @return int[]
     */
    public function getSize()
    {
        return [
            $this->width,
            $this->height
        ];
    }

    public function getType()
    {

    }
}

/* EOF */
