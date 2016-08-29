<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Util;

use AppBundle\Manager\ConfigurationManager;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class Mapper.
 */
class Mapper implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var string
     */
    const SIZE_DEFAULT = '640x400';

    /**
     * @var int
     */
    const MAX_AGE = 6000;

    /**
     * @var ConfigurationManager
     */
    protected $configurationManager;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $api;

    /**
     * @var AbstractAdapter
     */
    protected $cache;

    /**
     * @var string
     */
    protected $rootPath;

    /**
     * @var string
     */
    protected $outputPath;

    /**
     * @param ConfigurationManager $manager
     */
    public function setConfigManager(ConfigurationManager $manager)
    {
        $this->configurationManager = $manager;
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
     * @param string $sysPath
     * @param string $webPath
     */
    public function setCache(AbstractAdapter $cache)
    {
        $this->cache = $cache;
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
    public function generate($size = self::SIZE_DEFAULT)
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
    protected function getCacheItem($url)
    {
        return $this->cache->getItem(preg_replace('{[^a-z0-9]}i', '', $url));
    }

    /**
     * @return string
     */
    protected function getAddress()
    {
        return urlencode($this->configurationManager->value('address'));
    }

    /**
     * @param string $size
     *
     * @return mixed|string
     */
    protected function getUriCompiled($size)
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
     *
     * @return string
     */
    protected function refresh(CacheItemInterface $item, $link)
    {
        $item->set($contents = file_get_contents($link));
        $item->expiresAt(new \DateTime('@'.(time() + self::MAX_AGE)));
    }

    protected function getTemporaryFilePath(CacheItemInterface $item, $link)
    {
        $root = $this->outputPath;

        if (!file_exists($root)) {
            @mkdir($root, 0777);
        }

        return $root.DIRECTORY_SEPARATOR.hash('sha256', $item->getKey()).$this->guessExtension($link);
    }

    protected function guessExtension($link)
    {
        if (1 === preg_match('{format=([a-z]+)}i', $link, $matches)) {
            return '.'.$matches[1];
        }

        return '';
    }
}

/* EOF */
