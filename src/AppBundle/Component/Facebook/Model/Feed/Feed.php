<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Model\Feed;

use AppBundle\Component\Facebook\Model\AbstractModel;

/**
 * Class Feed.
 */
class Feed extends AbstractModel implements \IteratorAggregate
{
    /**
     * @var array[]
     */
    const MAPPING_DEFINITION = [
        'posts' => [
            'object_fqcn' => FeedPost::class,
            'object_coll' => true,
        ],
    ];

    /**
     * @var FeedPost[]
     */
    protected $posts;

    /**
     * @param array|null  $data
     * @param string|null $field
     *
     * @return static
     */
    public static function create(array $data = null, $key = null)
    {
        $instance = new static();

        if ($data) {
            $instance->hydrate($data, $key);
        }

        return $instance;
    }

    /**
     * @return \AppBundle\Component\Facebook\Model\Feed\FeedPost[]
     */
    public function toArray()
    {
        return $this->posts;
    }

    /**
     * @return \ArrayIterator|\AppBundle\Component\Facebook\Model\Feed\FeedPost[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->posts);
    }

    public function getPosts()
    {
        return $this->posts;
    }
}

/* EOF */
