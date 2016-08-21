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
        'updated_time' => DateTimeTransformer::class,
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
            'to_property' => 'createdOn',
        ],
        'updated_time' => [
            'to_property' => 'updatedOn',
        ],
        'permalink_url' => [
            'to_property' => 'permanentLink',
        ],
        'from' => [
            'to_property' => 'author',
        ],
        'attachments' => [
            'object_fqcn' => FeedAttachments::class,
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
    ];

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
    protected $permanentLink;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string[]
     */
    protected $properties;

    /**
     * @var bool
     */
    protected $published;

    /**
     * @var bool
     */
    protected $author;

    /**
     * @var string
     */
    protected $iconLink;

    /**
     * @var string|null
     */
    protected $message;

    /**
     * @var FeedAttachments[]|null
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
     * @return bool
     */
    public function hasMessage()
    {
        return $this->message !== null;
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
    public function getPermanentLink()
    {
        return $this->permanentLink;
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
     * @return \string[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @return boolean
     */
    public function isAuthor()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getIconLink()
    {
        return $this->iconLink;
    }

    /**
     * @return null|string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return FeedAttachments[]|null
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
}

/* EOF */
