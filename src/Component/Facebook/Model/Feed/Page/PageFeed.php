<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Model\Feed\Page;

use AppBundle\Component\Facebook\Model\AbstractModel;
use AppBundle\Component\Facebook\Model\Feed\FeedItem;

/**
 * Class PageFeed.
 */
class PageFeed extends AbstractModel implements \IteratorAggregate
{
    /**
     * @var string[]
     */
    const DATA_KEYS_EXCLUDED = ['id'];

    /**
     * @var array[]
     */
    const MAPPING_DEFINITION = [
        'feed' => [
            'object_fqcn' => FeedItem::class,
            'to_property' => 'items',
            'object_coll' => true,
        ],
    ];

    /**
     * @var string|int|null
     */
    protected $id;

    /**
     * @var FeedItem[]
     */
    protected $items;

    /**
     * @return \AppBundle\Component\Facebook\Model\Feed\FeedItem[]
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * @return \ArrayIterator|\AppBundle\Component\Facebook\Model\Feed\FeedItem[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @return int|null|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param array|null  $data
     * @param string|null $pageId
     *
     * @return $this
     */
    public function hydrate(array $data = null, $pageId = null)
    {
        $this->id = $pageId;

        parent::hydrate($data);

        return $this;
    }
}

/* EOF */
