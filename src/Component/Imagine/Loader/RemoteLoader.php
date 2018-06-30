<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Imagine\Loader;

use AppBundle\Component\Imagine\Loader\Cache\LoaderCache;
use AppBundle\Component\Imagine\Loader\Cache\LoaderCacheInterface;
use Liip\ImagineBundle\Exception\File\Loader\NotLoadableException;
use Liip\ImagineBundle\File\FileInterface;
use Liip\ImagineBundle\File\FilePath;
use Liip\ImagineBundle\Imagine\Data\Loader\LoaderInterface;
use Psr\Cache\CacheItemInterface;

class RemoteLoader implements LoaderInterface
{
    /**
     * @var LoaderCacheInterface
     */
    private $remoteCache;

    /**
     * @var string
     */
    private $temporaryDirectory;

    /**
     * @param string               $temporaryDirectory
     * @param LoaderCacheInterface $cache
     */
    public function __construct($temporaryDirectory, LoaderCacheInterface $cache)
    {
        $this->temporaryDirectory = LoaderCache::ensureValidCacheDirectory($temporaryDirectory);
        $this->remoteCache = $cache;
    }

    /**
     * @param string $url
     *
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @return FileInterface
     */
    public function find(string $url): FileInterface
    {
        if (!static::validateUrl($url)) {
            throw new NotLoadableException('Invalid remote URL: '.$url);
        }

        $file = $this->remoteCache->getItem(static::slugifyUrl(
            $url = static::sanitizeUrl($url)
        ));

        if (!$file->isHit()) {
            if (false === $remoteContents = @file_get_contents($url)) {
                throw new NotLoadableException(sprintf('Could not fetch remote URL %s', $url));
            }

            $file->set($remoteContents);
            $this->remoteCache->save($file);
        }

        return FilePath::create($this->writeTemporaryFile($url, $file));
    }

    /**
     * @param string             $url
     * @param CacheItemInterface $file
     *
     * @return string
     */
    private function writeTemporaryFile($url, CacheItemInterface $file)
    {
        $temporary = sprintf('%s%s.download', $this->temporaryDirectory, static::slugifyUrl($url));

        if (false === file_put_contents($temporary, $file->get())) {
            throw new NotLoadableException(sprintf('Unable to save temporary local copy (%s) of remote file (%s).', $temporary, $url));
        }

        return $temporary;
    }

    /**
     * @param string $url
     *
     * @return string
     */
    public static function slugifyUrl($url)
    {
        return preg_replace('{[\-]+}', '-', preg_replace('{[^a-z0-9]}i', '-', static::sanitizeUrl($url)));
    }

    /**
     * @param string $url
     *
     * @return string
     */
    private static function sanitizeUrl($url)
    {
        return filter_var($url, FILTER_SANITIZE_URL);
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    private static function validateUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}
