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
 * Class FeedMediaPhotoFormat.
 */
class FeedMediaPhotoFormat extends AbstractModel
{
    /**
     * @var array[]
     */
    const MAPPING_DEFINITION = [
        'source' => [
            'to_property' => 'link'
        ],
    ];

    /**
     * @var int
     */
    protected $height;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var string
     */
    protected $link;

    protected function assignDataToModel()
    {
        foreach ($this->data as $key => $data) {
            $this->assignFieldToModel($data, $key);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getEdgeShortest()
    {
        return $this->width > $this->height ? $this->height : $this->width;
    }

    /**
     * @return int
     */
    public function getEdgeLongest()
    {
        return $this->width < $this->height ? $this->height : $this->width;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }
}

/* EOF */
