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
use AppBundle\Component\Facebook\Transformer\AttachmentTargetTransformer;
use AppBundle\Component\Facebook\Transformer\TransformerInterface;

/**
 * Category FeedAttachment
 */
class FeedAttachment extends AbstractModel
{
    /**
     * @var TransformerInterface[]
     */
    const VALUE_TRANSFORMERS = [
        'target' => AttachmentTargetTransformer::class,
    ];

    /**
     * @var array[]
     */
    const MAPPING_DEFINITION = [
        'url' => [
            'to_property' => 'link',
        ],
        'subattachments' => [
            'to_property' => 'subAttachments',
            'object_fqcn' => FeedAttachment::class,
            'object_coll' => true,
        ],
        'media' => [
            'object_fqcn' => FeedMedia::class,
            'object_coll' => true,
        ],
        'target' => [
            'to_property' => 'targetLink',
        ],
    ];

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var FeedMedia[]
     */
    protected $media;

    /**
     * @var FeedAttachment[]
     */
    protected $subAttachments;

    /**
     * @var string
     */
    protected $targetLink;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $title;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return FeedMedia[]
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @return bool
     */
    public function hasMedia()
    {
        return count($this->media) !== 0;
    }

    /**
     * @return FeedAttachment[]
     */
    public function getSubAttachments()
    {
        return $this->subAttachments;
    }

    /**
     * @return bool
     */
    public function hasSubAttachments()
    {
        return count($this->subAttachments) !== 0;
    }

    /**
     * @return string
     */
    public function getTargetLink()
    {
        return $this->targetLink;
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function hasDescription()
    {
        return $this->description !== null;
    }

    /**
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return bool
     */
    public function hasTitle()
    {
        return $this->title !== null;
    }

    /**
     * @return bool
     */
    public function isImage()
    {
        return $this->type === 'photo';
    }

    /**
     * @return bool
     */
    public function isAlbum()
    {
        return $this->type === 'album';
    }

    /**
     * @return bool
     */
    public function isVideo()
    {
        return $this->type === 'video_inline';
    }
}

/* EOF */
