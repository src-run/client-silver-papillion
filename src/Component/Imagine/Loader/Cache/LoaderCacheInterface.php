<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Imagine\Loader\Cache;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerAwareInterface;

/**
 * Interface LoaderCacheInterface.
 */
interface LoaderCacheInterface extends CacheItemPoolInterface, LoggerAwareInterface
{
    /**
     * @var int
     */
    const DEFAULT_LIFETIME = 1800;

    /**
     * @var string
     */
    const DEFAULT_NAMESPACE = 'liip-loader-cache';

    /**
     * @param string $id
     *
     * @return string
     */
    public static function getItemBasePath($id);
}

/* EOF */
