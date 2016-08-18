<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Provider;

use AppBundle\Component\Facebook\Model\Feed\Page\PageFeed;

/**
 * Category FeedProviderInterface
 */
interface FeedProviderInterface
{
    /**
     * @return PageFeed
     */
    public function getFeed();

    /**
     * @return bool
     */
    public function hasFeed();
}

/* EOF */
