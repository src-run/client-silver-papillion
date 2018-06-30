<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Util;

use AppBundle\Manager\ConfigurationManager;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\VarDumper\VarDumper;

class Mapper
{
   /**
     * @var string
     */
    const SIZE_DEFAULT = '640x400';

    /**
     * @var int
     */
    const MAX_AGE = 6000;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var string
     */
    private $api;

    /**
     * @var AdapterInterface
     */
    private $cache;

    /**
     * @var string
     */
    private $rootPath;

    /**
     * @var string
     */
    private $outputPath;

    /**
     * @var ConfigurationManager
     */
    private $configuration;

    /**
     * @param CacheItemPoolInterface $cache
     * @param ConfigurationManager   $configuration
     */
    public function __construct(CacheItemPoolInterface $cache, ConfigurationManager $configuration)
    {
        $this->cache = $cache;
        $this->configuration = $configuration;
    }

    /**
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @param string $api
     */
    public function setApi($api)
    {
        $this->api = $api;
    }

    /**
     * @param string $output
     */
    public function setOutputPath($root, $output)
    {
        $this->rootPath = $root;
        $this->outputPath = $output;
    }

    /**
     * @param string $size
     *
     * @return string
     */
    public function generate($size = self::SIZE_DEFAULT): string
    {
        $link = $this->getUriCompiled($size);
        $item = $this->getCacheItem($link);

        if (file_exists($temp = $this->getTemporaryFilePath($item, $link))) {
            return str_replace($this->rootPath, '', $this->getTemporaryFilePath($item, $link));
        }

        if (!$item->isHit()) {
            $this->refresh($item, $link);
        }

        file_put_contents($temp, $item->get());

        return str_replace($this->rootPath, '', $temp);
    }

    /**
     * @param string $url
     *
     * @return CacheItemInterface
     */
    protected function getCacheItem($url): CacheItemInterface
    {
        return $this->cache->getItem(preg_replace('{[^a-z0-9]}i', '', $url));
    }

    /**
     * @return string
     */
    protected function getAddress(): string
    {
        return urlencode($this->configuration->value('address'));
    }

    /**
     * @param string $size
     *
     * @return string
     */
    protected function getUriCompiled($size): string
    {
        $uri = $this->uri;

        foreach (['api_key' => $this->api, 'address' => $this->getAddress(), 'size' => $size] as $search => $replace) {
            $uri = str_replace('${'.$search.'}', $replace, $uri);
        }

        return $uri;
    }

    /**
     * @param CacheItemInterface $item
     * @param string             $link
     */
    protected function refresh(CacheItemInterface $item, $link): void
    {
        $item->set($contents = file_get_contents($link));
        $item->expiresAt(new \DateTime('@'.(time() + self::MAX_AGE)));
    }

    /**
     * @param CacheItemInterface $item
     * @param string             $link
     *
     * @return string
     */
    protected function getTemporaryFilePath(CacheItemInterface $item, $link): string
    {
        $root = $this->outputPath;

        if (!file_exists($root)) {
            @mkdir($root, 0777);
        }

        return $root.DIRECTORY_SEPARATOR.hash('sha256', $item->getKey()).$this->guessExtension($link);
    }

    /**
     * @param string $link
     *
     * @return string
     */
    protected function guessExtension($link): string
    {
        if (1 === preg_match('{format=([a-z]+)}i', $link, $matches)) {
            return '.'.$matches[1];
        }

        return '';
    }
}
