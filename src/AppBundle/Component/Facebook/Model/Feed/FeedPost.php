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
 * Class FeedPost.
 */
class FeedPost extends AbstractModel
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
            'to_property' => 'permaLink',
        ],
        'from' => [
            'to_property' => 'author',
        ],
        'comments' => [
            'object_fqcn' => Comment::class,
            'object_coll' => true,
        ],
        'reactions' => [
            'object_fqcn' => Reaction::class,
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
    protected $permaLink;

    /**
     * @var string
     */
    protected $iconLink;

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
     * @var string
     */
    protected $type;

    /**
     * @var Media[]
     */
    protected $attachments;

    /**
     * @var Comment[]|null
     */
    protected $comments;

    /**
     * @var Reaction[]|null
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
     * @return Media[]
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
        return count($this->attachments) > 0;
    }

    /**
     * @return bool
     */
    public function isVideo()
    {
        return count($this->getVideos()) > 0;
    }

    /**
     * @return bool
     */
    public function isPhoto()
    {
        return count($this->getPhotos()) > 0;
    }

    /**
     * @return MediaPhoto[]
     */
    public function getPhotos()
    {
        return array_filter($this->getMedia(), function (Media $m) {
            return $m->isPhoto();
        });
    }

    /**
     * @return MediaPhoto[]
     */
    public function getVideos()
    {
        return array_filter($this->getMedia(), function (Media $m) {
            return $m->isVideo();
        });
    }

    /**
     * @return Media[]
     */
    public function getMedia()
    {
        return $this->attachments;
    }

    /**
     * @return bool
     */
    public function hasMedia()
    {
        return count($this->attachments) > 0;
    }

    /**
     * @return Media
     */
    public function getFirstMedia()
    {
        return $this->attachments[0];
    }

    /**
     * @return Comment[]|null
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
     * @return Reaction[]|null
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
     * @return Reaction[]
     */
    public function getReactionLikes()
    {
        if (count($this->reactions) === 0) {
            return [];
        }

        return array_filter($this->reactions, function (Reaction $r) {
            return $r->isLike();
        });
    }
}

/* EOF */
