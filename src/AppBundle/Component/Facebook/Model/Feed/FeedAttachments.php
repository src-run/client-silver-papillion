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
 * Category FeedAttachments
 */
class FeedAttachments extends AbstractModel
{
    /**
     * @var FeedMedia[]|FeedMediaVideo[]|FeedMediaPhoto[]
     */
    private $attachments = [];

    /**
     * @param array $attachments
     *
     * @return $this
     */
    public function hydrate(array $attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->attachments);
    }

    /**
     * @return FeedMedia[]|FeedMediaPhoto[]|FeedMediaVideo[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }
}

/* EOF */
