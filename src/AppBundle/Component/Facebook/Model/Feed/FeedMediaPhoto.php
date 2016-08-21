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
 * Class FeedMediaPhoto.
 */
class FeedMediaPhoto extends FeedMedia
{
    /**
     * @var array[]
     */
    const MAPPING_DEFINITION = [
        'permalink_url' => [
            'to_property' => 'link',
        ],
        'images' => [
            'to_property' => 'formats',
            'object_fqcn' => FeedMediaPhotoFormat::class,
            'object_coll' => true,
        ],
        'created_time' => [
            'to_property' => 'createdOn'
        ],
        'updated_time' => [
            'to_property' => 'updatedOn'
        ],
        'name' => [
            'to_property' => 'description'
        ],
    ];

    /**
     * @return bool
     */
    public function hasFormats() : bool
    {
        return $this->formats !== null && count($this->formats) > 0;
    }

    /**
     * @param int  $minimumEdge
     * @param bool $bestMatch
     *
     * @return FeedMediaPhotoFormat[]
     */
    public function getFormatsLargerThan($minimumEdge = 1280, $bestMatch = true) : array
    {
        $formats = $this->getFormatsFiltered(function (FeedMediaPhotoFormat $f) use ($minimumEdge) {
            return $f->getEdgeLongest() >= $minimumEdge;
        });

        if ($bestMatch) {
            $formats = count($formats) > 0 ? $formats : $this->formats;
        }

        usort($formats, function (FeedMediaPhotoFormat $a, FeedMediaPhotoFormat $b) {
            return $a->getEdgeLongest() > $b->getEdgeLongest();
        });

        return $formats;
    }

    /**
     * @param int  $minimumEdge
     * @param bool $bestMatch
     *
     * @return FeedMediaPhotoFormat|null
     */
    public function getSingleFormatLargerThan($minimumEdge = 1280, $bestMatch = true) : FeedMediaPhotoFormat
    {
        $formats = $this->getFormatsLargerThan($minimumEdge, $bestMatch);

        return count($formats) > 0 ? $formats[0] : null;
    }

    /**
     * @param int  $maximumEdge
     * @param bool $bestMatch
     *
     * @return FeedMediaPhotoFormat[]
     */
    public function getFormatsSmallerThan($maximumEdge = 1280, $bestMatch = true) : array
    {
        $formats = $this->getFormatsFiltered(function (FeedMediaPhotoFormat $f) use ($maximumEdge) {
            return $f->getEdgeLongest() <= $maximumEdge;
        });

        if ($bestMatch) {
            $formats = count($formats) > 0 ? $formats : $this->formats;
        }

        usort($formats, function (FeedMediaPhotoFormat $a, FeedMediaPhotoFormat $b) {
            return $a->getEdgeLongest() < $b->getEdgeLongest();
        });

        return $formats;
    }

    /**
     * @param int  $maximumEdge
     * @param bool $bestMatch
     *
     * @return FeedMediaPhotoFormat|null
     */
    public function getSingleFormatSmallerThan($maximumEdge = 1280, $bestMatch = true) : FeedMediaPhotoFormat
    {
        $formats = $this->getFormatsLargerThan($maximumEdge, $bestMatch);

        return count($formats) > 0 ? $formats[0] : null;
    }

    /**
     * @param \Closure $predicate
     *
     * @return FeedMediaPhotoFormat[]
     */
    public function getFormatsFiltered(\Closure $predicate) : array
    {
        return array_filter($this->formats, $predicate);
    }
}

/* EOF */
