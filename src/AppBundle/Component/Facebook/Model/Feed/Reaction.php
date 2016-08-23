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
 * Class Reaction.
 */
class Reaction extends AbstractModel
{
    /**
     * @var array[]
     */
    const MAPPING_DEFINITION = [
        'name' => [
            'to_property' => 'author',
        ],
    ];

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $author;

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
    public function isLike()
    {
        return $this->type == 'LIKE';
    }

    /**
     * @return string|null
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return bool
     */
    public function hasAuthor()
    {
        return $this->author !== null;
    }
}

/* EOF */
