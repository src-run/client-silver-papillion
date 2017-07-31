<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Environment;

class Environment
{
    /**
     * @var string
     */
    private $environment;

    /**
     * @var bool
     */
    private $cache;

    /**
     * @param string $environment
     */
    public function __construct(string $environment, bool $cache)
    {
        $this->environment = $environment;
        $this->cache = $cache;
    }

    /**
     * @return bool
     */
    public function isProduction(): bool
    {
        return $this->environment === 'prod';
    }

    /**
     * @return bool
     */
    public function isDevelopment(): bool
    {
        return $this->environment === 'dev';
    }

    /**
     * @return bool
     */
    public function isCacheEnabled(): bool
    {
        return $this->cache;
    }
}
