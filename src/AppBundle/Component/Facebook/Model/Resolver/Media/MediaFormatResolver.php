<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Model\Resolver\Media;

use AppBundle\Component\Facebook\Model\Feed\Media;
use AppBundle\Component\Facebook\Model\Feed\MediaFormat;
use AppBundle\Component\Facebook\Model\Feed\MediaFormatPhoto;
use AppBundle\Component\Facebook\Model\Feed\MediaFormatVideo;

/**
 * Class MediaFormatResolver.
 */
class MediaFormatResolver
{
    /**
     * @var MediaFormat[]
     */
    private $formats = [];

    /**
     * @var MediaFormatPhoto
     */
    private $images = [];

    /**
     * @var MediaFormatVideo
     */
    private $videos = [];

    /**
     * @var \Closure
     */
    private $defaultSelector;

    /**
     * @param MediaFormat[] $mediaFormats
     */
    public function __construct(MediaFormat ...$mediaFormats)
    {
        $this->doSortAndLocalAssigns($mediaFormats);
    }

    /**
     * @param MediaFormat[] ...$formats
     */
    private function doSortAndLocalAssigns(MediaFormat ...$formats)
    {
        foreach ($formats as $f) {
            $this->doSplitAssigsByType($f);
        }
    }

    private function doSplitAssigsByType(MediaFormat $format)
    {
        switch ($format->getType()) {
            case
        }
    }

    /**
     * @param  Media[] ...$formats
     *
     * @return Media[]
     */
    public static function orderLgToSm(...$formats)
    {
        usort($formats, function (MediaFormat $a, MediaFormat $b) {
            return static::compareSize($a->getSize(), $b->getSize(), '<');
        });

        return $formats;
    }

    /**
     * @param  Media[] ...$formats
     *
     * @return Media[]
     */
    public static function orderSmToLg(...$formats)
    {
        usort($formats, function (MediaFormat $a, MediaFormat $b) {
            return static::compareSize($a->getSize(), $b->getSize(), '>');
        });

        return $formats;
    }

    /**
     * @param  int[]|string[] $a
     * @param  int[]|string[] $b
     * @param  string         $opr
     * @param  bool           $max
     *
     * @return int
     */
    private static function compareSize(array $a, array $b, $opr, $max = true)
    {
        $arguments = array_map(function ($z) use ($max) {
            return $max !== false ? max($z) : min($z);
        }, [$a, $b]);

        $arguments[] = $opr;
        if (false === $return = version_compare(...$arguments)) {
            $return = 0;
        }

        return $return;
    }

    public function setDefaultSelector(\Closure $default)
    {
        $this->defaultSelector = $default;
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
     * @return Media[]
     */
    public function formatLessThanSize($size)
    {
        $filtered = array_filter($this->formats, function (MediaFormat $f) use ($size) {
            return max($f->getSize()) < $size;
        });

        dump($filtered);

        $ordered = $this->orderFormats($formats);

        dump($ordered);
        die();

        $formats = $this->formats !== null ? $this->formats : [];
        $filters = array_filter($formats, function (MediaFormat $f) use ($size) {
            return max($f->getSize()) < $size;
        });

        return $this->orderFormats($filters);
    }

    /**
     * @param int $size
     *
     * @return Media
     */
    public function SmrEdgeThan($size)
    {
        $formats = $this->getFormatsSmallerThan($size);
        $formats = count($formats) > 0 ? $formats : ($this->formats !== null ? $this->formats : []);
        $formats = $this->orderFormats($formats);

        return array_shift($formats);
    }

    /**
     * @param int $size
     *
     * @return Media[]
     */
    public function formatsOfLargerEdgeThan($size)
    {
        dump($size);
        $formats = $this->formats !== null ? $this->formats : [];
        $filters = array_filter($formats, function (MediaFormat $f) use ($size) {
            return max($f->getSize()) > $size;
        });
        dump($filters);


        return $this->orderFormats($filters);
    }

    /**
     * @param int $size
     *
     * @return Media
     */
    public function LgrEdgeThan($size)
    {
        $filtered = array_filter($this->formats, function (MediaFormat $f) use ($size) {
            return max($f->getSize()) < $size;
        });

        dump($filtered);
        $ordered = $this->orderFormats($formats);

        dump($ordered);
        die();


        $formats = $this->formatsOfLargerEdgeThan($size);
        $formats = count($formats) > 0 ? $formats : ($this->formats !== null ? $this->formats : []);
        $formats = $this->orderFormats($formats);

        return array_shift($formats);
    }

    /**
     * @param Media[] $formats
     *
     * @return Media[]
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
