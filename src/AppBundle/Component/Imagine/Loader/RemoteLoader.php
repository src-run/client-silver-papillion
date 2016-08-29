<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Imagine\Loader;

use AppBundle\Component\Imagine\Loader\Cache\LoaderCache;
use AppBundle\Component\Imagine\Loader\Cache\LoaderCacheInterface;
use Liip\ImagineBundle\Binary\Loader\LoaderInterface;
use Liip\ImagineBundle\Binary\MimeTypeGuesserInterface;
use Liip\ImagineBundle\Exception\Binary\Loader\NotLoadableException;
use Liip\ImagineBundle\Model\FileBinary;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesserInterface;

/**
 * Class RemoteLoader.
 */
class RemoteLoader implements LoaderInterface
{
    /**
     * @var MimeTypeGuesserInterface
     */
    private $mimeTypeGuesser;

    /**
     * @var ExtensionGuesser
     */
    private $extensionGuesser;

    /**
     * @var LoaderCacheInterface
     */
    private $remoteCache;

    /**
     * @var string
     */
    private $temporaryDirectory;

    /**
     * @param string                    $temporaryDirectory
     * @param MimeTypeGuesserInterface  $mimeType
     * @param ExtensionGuesserInterface $extension
     * @param LoaderCacheInterface      $cache
     */
    public function __construct($temporaryDirectory, MimeTypeGuesserInterface $mimeType, ExtensionGuesserInterface $extension, LoaderCacheInterface $cache)
    {
        $this->temporaryDirectory = LoaderCache::ensureValidCacheDirectory($temporaryDirectory);
        $this->mimeTypeGuesser = $mimeType;
        $this->extensionGuesser = $extension;
        $this->remoteCache = $cache;
    }

    /**
     * @param string $url
     *
     * @return FileBinary
     */
    public function find($url)
    {
        if (!static::validateUrl($url)) {
            throw new NotLoadableException('Invalid remote URL: '.$url);
        }

        $this->handleRemoteFileLoad($url);
        list($absolutePath, , $mimeType, $extension) = $this->info($url);


        return new FileBinary($absolutePath, $mimeType, $extension);
    }

    /**
     * @param string $url
     *
     * @return MimeTypeGuesserInterface[]|ExtensionGuesserInterface[]|string[]
     */
    public function info($url)
    {
        $url = static::sanitizeUrl($url);
        $file = $this->getCacheEntry($url);

        $mimeType = $this->mimeTypeGuesser->guess($file->get());
        $extension = $this->extensionGuesser->guess($mimeType);

        return [
            $this->writeTemporaryFile($url, $file, $extension),
            sprintf('%s.%s', static::slugifyUrl($url), $extension),
            $mimeType,
            $extension,
        ];
    }

    /**
     * @param string $url
     *
     * @return MimeTypeGuesserInterface[]|ExtensionGuesserInterface[]|string[]
     */
    private function handleRemoteFileLoad($url)
    {
        $url = static::sanitizeUrl($url);
        $file = $this->getCacheEntry($url);

        if (!$file->isHit()) {
            $this->refreshCacheEntry($url, $file);
        }

        return $this->info($url);
    }

    /**
     * @param string $url
     *
     * @return \Psr\Cache\CacheItemInterface
     */
    public function getCacheEntry($url)
    {
        return $this->remoteCache->getItem(static::slugifyUrl($url));
    }

    /**
     * @param string             $url
     * @param CacheItemInterface $file
     *
     * @return string
     */
    private function refreshCacheEntry($url, CacheItemInterface $file)
    {
        $remoteContents = file_get_contents($url);

        if (!$remoteContents) {
            throw new NotLoadableException(sprintf('Could not fetch remote URL %s', $url));
        }

        $file->set($remoteContents);
        $this->remoteCache->save($file);
    }

    /**
     * @param string             $url
     * @param CacheItemInterface $file
     * @param string             $extension
     *
     * @return string
     */
    private function writeTemporaryFile($url, CacheItemInterface $file, $extension)
    {
        $temporaryDirectory = sprintf('%s%s.%s', $this->temporaryDirectory, static::slugifyUrl($url), $extension);

        if (false === file_put_contents($temporaryDirectory, $file->get())) {
            throw new NotLoadableException(sprintf('Unable to save temporary local copy (%s) of remote file (%s).', $temporaryDirectory, $url));
        }

        return $temporaryDirectory;
    }

    /**
     * @param string $url
     *
     * @return string
     */
    static public function slugifyUrl($url)
    {
        return preg_replace('{[\-]+}', '-', preg_replace('{[^a-z0-9]}i', '-', static::sanitizeUrl($url)));
    }

    /**
     * @param string $url
     *
     * @return string
     */
    static private function sanitizeUrl($url)
    {
        return filter_var($url, FILTER_SANITIZE_URL);
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    static private function validateUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}

/* EOF */
