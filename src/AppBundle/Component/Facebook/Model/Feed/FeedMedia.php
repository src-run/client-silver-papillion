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
class FeedMedia extends AbstractModel
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * @var \DateTime
     */
    protected $updatedOn;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var FeedMediaVideoFormat[]|FeedMediaPhotoFormat[]
     */
    protected $formats;

    /**
     * @var string
     */
    protected $description;

    protected function assignDataToModel()
    {
        foreach ($this->data as $key => $data) {
            $this->assignFieldToModel($data, $key);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return FeedMediaVideoFormat[]|FeedMediaPhotoFormat[]
     */
    public function getFormats()
    {
        return $this->formats;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}

/* EOF */
