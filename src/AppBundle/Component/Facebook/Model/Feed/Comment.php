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
 * Class Comment.
 */
class Comment extends AbstractModel
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
        'created_time' => [
            'to_property' => 'createdOn',
        ],
        'from' => [
            'to_property' => 'author',
        ],
    ];

    /**
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * @var string
     */
    protected $author;

    /**
     * @var string
     */
    protected $message;

    /**
     * @return mixed
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }
}

/* EOF */
