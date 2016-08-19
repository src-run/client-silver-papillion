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
use AppBundle\Component\Facebook\Transformer\AuthorTransformer;
use AppBundle\Component\Facebook\Transformer\DateTimeTransformer;
use AppBundle\Component\Facebook\Transformer\TransformerInterface;

/**
 * Class FeedItem.
 */
class FeedItem extends AbstractModel
{
    /**
     * @var TransformerInterface[]
     */
    const VALUE_TRANSFORMERS = [
        'created_time' => DateTimeTransformer::class,
        'from' => AuthorTransformer::class,
    ];

    /**
     * @var array[]
     */
    const MAPPING_DEFINITION = [
        'is_published' => [
            'to_property' => 'published',
        ],
        'created_time' => [
            'to_property' => 'date',
        ],
        'permalink_url' => [
            'to_property' => 'permaLink',
        ],
        'from' => [
            'to_property' => 'author',
        ],
        'attachments' => [
            'object_fqcn' => FeedAttachment::class,
            'object_coll' => true,
        ],
        'comments' => [
            'object_fqcn' => FeedComment::class,
            'object_coll' => true,
        ],
        'reactions' => [
            'object_fqcn' => FeedReaction::class,
            'object_coll' => true,
        ],
        'icon' => [
            'to_property' => 'iconLink',
        ],
        'source' => [
            'to_property' => 'sourceLink',
        ],
    ];

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $permaLink;

    /**
     * @var string
     */
    protected $iconLink;

    /**
     * @var string
     */
    protected $sourceLink;

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var bool
     */
    protected $published;

    /**
     * @var bool
     */
    protected $author;

    /**
     * @var string|null
     */
    protected $message;

    /**
     * @var string|null
     */
    protected $story;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var FeedAttachment[]|null
     */
    protected $attachments;

    /**
     * @var FeedComment[]|null
     */
    protected $comments;

    /**
     * @var FeedReaction[]|null
     */
    protected $reactions;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPermaLink()
    {
        return $this->permaLink;
    }

    /**
     * @return string
     */
    public function getIconLink()
    {
        return $this->iconLink;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isVideo()
    {
        return $this->getType() === 'video';
    }

    /**
     * @return bool
     */
    public function isPhoto()
    {
        return $this->getType() === 'photo';
    }

    /**
     * @return bool
     */
    public function isStatus()
    {
        return $this->getType() === 'status';
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @return null|string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return null|string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return bool
     */
    public function hasMessage()
    {
        return $this->message !== null;
    }

    /**
     * @return null|string
     */
    public function getStory()
    {
        return $this->story;
    }

    /**
     * @return bool
     */
    public function hasStory()
    {
        return $this->story !== null;
    }

    /**
     * @return FeedAttachment[]|null
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @return bool
     */
    public function hasAttachments()
    {
        return $this->attachments !== null;
    }

    /**
     * @return FeedComment[]|null
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return bool
     */
    public function hasComments()
    {
        return $this->comments !== null;
    }

    /**
     * @return FeedReaction[]|null
     */
    public function getReactions()
    {
        return $this->reactions;
    }

    /**
     * @return bool
     */
    public function hasReactions()
    {
        return $this->reactions !== null;
    }

    /**
     * @return string
     */
    public function getSourceLink()
    {
        return $this->sourceLink;
    }

    /**
     * @param string $sourceLink
     */
    public function setSourceLink($sourceLink)
    {
        $this->sourceLink = $sourceLink;
    }

    /**
     * @return bool
     */
    public function hasSourceLink()
    {
        return $this->sourceLink !== null;
    }
}

/* EOF */
