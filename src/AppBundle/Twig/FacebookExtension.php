<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

use AppBundle\Component\Facebook\Model\Feed\Page\PageFeed;
use AppBundle\Component\Facebook\Provider\FeedProviderInterface;

/**
 * Class FacebookExtension
 */
class FacebookExtension extends \Twig_Extension
{
    /**
     * @var FeedProviderInterface
     */
    private $provider;

    /**
     * @param Slugger $slugger
     */
    public function setProvider(FeedProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return \Twig_Function[]
     */
    public function getFunctions()
    {
        return array(
            new \Twig_Function('has_fb_feed', [$this, 'hasFacebookFeed']),
            new \Twig_Function('get_fb_feed', [$this, 'getFacebookFeed']),
        );
    }

    /**
     * @return bool
     */
    public function hasFacebookFeed()
    {
        return $this->provider->hasFeed();
    }

    /**
     * @return PageFeed
     */
    public function getFacebookFeed()
    {
        return $this->provider->getFeed();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'facebook_extension';
    }
}

/* EOF */
