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
abstract class Media extends AbstractModel
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
     * @var string
     */
    protected $text;

    /**
     * @var MediaFormat[]
     */
    protected $formats = [];

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
     * @return MediaFormat[]
     */
    public function getFormats()
    {
        return $this->formats;
    }

    /**
     * @return bool
     */
    public function hasFormats()
    {
        return count($this->formats) > 0;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return bool
     */
    public function hasText()
    {
        return $this->text !== null;
    }

    /**
     * @return bool
     */
    public function isAlbum()
    {
        return static::class === MediaAlbum::class;
    }

    /**
     * @return bool
     */
    public function isPhoto()
    {
        return static::class === MediaPhoto::class;
    }

    /**
     * @return bool
     */
    public function isVideo()
    {
        return static::class === MediaVideo::class;
    }

    /**
     * @param int $size
     *
     * @return MediaFormat[]
     */
    public function formatsLgrThan($size)
    {
        $formats = array_filter($this->formats === null ? [] : $this->formats, function (MediaFormat $f) use ($size) {
            return max($f->getSize()) > $size;
        });

        return $this->orderFormats($formats);
    }

    /**
     * @param int $size
     *
     * @return MediaFormat
     */
    public function formatLgrThan($size)
    {
        $formats = $this->formatsLgrThan($size);

        if (count($formats) === 0) {
            $formats = $this->orderFormats($this->formats);
        }

        return array_shift($formats);
    }

    /**
     * @param int $size
     *
     * @return MediaFormat[]
     */
    public function formatsSlrThan($size)
    {
        $formats = array_filter($this->formats === null ? [] : $this->formats, function (MediaFormat $f) use ($size) {
            return max($f->getSize()) < $size;
        });

        return $this->orderFormats($formats);
    }

    /**
     * @param int $size
     *
     * @return MediaFormat
     */
    public function formatSlrThan($size)
    {
        $formats = $this->formatsSlrThan($size);

        if (count($formats) === 0) {
            $formats = $this->orderFormats($this->formats);
        }

        return array_shift($formats);
    }

    /**
     * @param MediaFormat[] $formats
     *
     * @return MediaFormat[]
     */
    protected function orderFormats(array $formats)
    {
        usort($formats, function (MediaFormat $a, MediaFormat $b) {
            return max($a->getSize()) < max($b->getSize());
        });

        return $formats;
    }
}

/* EOF */
