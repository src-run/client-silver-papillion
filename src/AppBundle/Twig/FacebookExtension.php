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

use AppBundle\Component\Environment\Environment;
use AppBundle\Component\Facebook\Model\AbstractModel;
use AppBundle\Component\Facebook\Provider\ProviderInterface;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

/**
 * Class FacebookExtension.
 */
class FacebookExtension extends TwigExtension
{
    /**
     * @var Environment
     */
    private $environment;

    /**
     * @var ProviderInterface
     */
    private $provider;

    /**
     * @param ProviderInterface $provider
     * @param Environment       $environment
     */
    public function __construct(ProviderInterface $provider, Environment $environment)
    {
        $this->provider = $provider;
        $this->environment = $environment;

        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('is_facebook_feed_direct', [$this, 'isFacebookFeedDirect']),
            new TwigFunctionDefinition('is_facebook_feed_cached', [$this, 'isFacebookFeedCached']),
            new TwigFunctionDefinition('facebook_feed_direct',    [$this, 'facebookFeedDirect']),
            new TwigFunctionDefinition('facebook_feed_cached',    [$this, 'facebookFeedCached']),
        ]);
    }

    /**
     * @return bool
     */
    public function isFacebookFeedDirect()
    {
        return $this->provider->has();
    }

    /**
     * @return AbstractModel
     */
    public function facebookFeedDirect()
    {
        return $this->provider->get();
    }

    /**
     * @return bool
     */
    public function isFacebookFeedCached()
    {
        return $this->provider->hasCached();
    }

    /**
     * @return AbstractModel||PageFeed
     */
    public function facebookFeedCached()
    {
        return $this->provider->getCached();
    }
}

/* EOF */
