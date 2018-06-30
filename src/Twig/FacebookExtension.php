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
use AppBundle\Component\Facebook\Provider\ProviderInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FacebookExtension extends AbstractExtension
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
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_facebook_feed_direct', function () {
                return $this->provider->has();
            }),
            new TwigFunction('is_facebook_feed_cached', function () {
                return $this->provider->hasCached();
            }),
            new TwigFunction('facebook_feed_direct', function () {
                return $this->provider->get();
            }),
            new TwigFunction('facebook_feed_cached', function () {
                return $this->provider->getCached();
            }),
        ];
    }
}
