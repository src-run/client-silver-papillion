<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Imagine\Resolver;

use AppBundle\Component\Imagine\Loader\RemoteLoader;
use Liip\ImagineBundle\Imagine\Cache\Resolver\WebPathResolver;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\RequestContext;

/**
 * Class RemoteLoader.
 */
class RemoteResolver extends WebPathResolver
{
    /**
     * @var RemoteLoader
     */
    private $loader;

    /**
     * @param Filesystem     $filesystem
     * @param RequestContext $requestContext
     * @param RemoteLoader   $remoteLoader
     * @param string         $webRootDir
     * @param string         $cachePrefix
     */
    public function __construct(Filesystem $filesystem, RequestContext $requestContext, RemoteLoader $loader, $webRootDir, $cachePrefix = 'media/cache')
    {
        parent::__construct($filesystem, $requestContext, $webRootDir, $cachePrefix);

        $this->loader = $loader;
    }

    protected function getFilePath($path, $filter)
    {
        return sprintf('%s/%s', $this->webRoot, $this->getFileUrl($path, $filter));
    }

    protected function getFileUrl($path, $filter)
    {
        list(, $relativePath) = $this->loader->info($path);

        return sprintf('%s/%s/%s', $this->cachePrefix, $filter, ltrim($relativePath, '/'));
    }
}

/* EOF */
