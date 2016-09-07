<?php

/*
 * This file is part of the `src-run/srw-client-silverpapillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

use AppBundle\Component\Facebook\Model\Feed\Page\PageFeed;
use AppBundle\Component\Facebook\Provider\ProviderInterface;

/**
 * Class FacebookExtension.
 */
class FacebookExtension extends \Twig_Extension
{
    /**
     * @var ProviderInterface
     */
    private $provider;

    /**
     * @param Slugger $slugger
     */
    public function setProvider(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return \Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('has_fb_feed', [$this, 'hasFacebookFeed']),
            new \Twig_Function('get_fb_feed', [$this, 'getFacebookFeed']),
            new \Twig_Function('has_fb_feed_cached', [$this, 'hasFacebookFeedCached']),
            new \Twig_Function('get_fb_feed_cached', [$this, 'getFacebookFeedCached']),
        ];
    }

    /**
     * @return bool
     */
    public function hasFacebookFeed()
    {
        return $this->provider->has();
    }

    /**
     * @return PageFeed
     */
    public function getFacebookFeed()
    {
        return $this->provider->get();
    }

    /**
     * @return bool
     */
    public function hasFacebookFeedCached()
    {
        return $this->provider->hasCached();
    }

    /**
     * @return PageFeed
     */
    public function getFacebookFeedCached()
    {
        return $this->provider->getCached();
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
