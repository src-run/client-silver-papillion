<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

use AppBundle\Component\Facebook\Model\AbstractModel;
use AppBundle\Component\Facebook\Provider\ProviderInterface;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class FacebookExtension.
 */
class FacebookExtension extends TwigExtension
{
    /**
     * @var ProviderInterface
     */
    private $provider;

    public function __construct()
    {
        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('has_fb_feed', [$this, 'hasFacebookFeed']),
            new TwigFunctionDefinition('get_fb_feed', [$this, 'getFacebookFeed']),
            new TwigFunctionDefinition('has_fb_feed_cached', [$this, 'hasFacebookFeedCached']),
            new TwigFunctionDefinition('get_fb_feed_cached', [$this, 'getFacebookFeedCached']),
        ]);
    }

    /**
     * @param ProviderInterface $provider
     */
    public function setProvider(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return bool
     */
    public function hasFacebookFeed()
    {
        return $this->provider->has();
    }

    /**
     * @return AbstractModel
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
}

/* EOF */
