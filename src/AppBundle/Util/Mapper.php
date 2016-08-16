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
     * @var string
     */
    protected $cacheSysPath;

    /**
     * @var string
     */
    protected $cacheWebPath;

    /**
     * @param ConfigurationManager $manager
     */
    public function setConfigurationManager(ConfigurationManager $manager)
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
    public function setCachePaths($sysPath, $webPath)
    {
        $this->cacheSysPath = $sysPath;
        $this->cacheWebPath = $webPath;
    }

    /**
     * @param string $size
     *
     * @return string
     */
    public function generate($size = self::SIZE_DEFAULT)
    {
        $uri = $this->getUriCompiled($size);

        return $this->fetch($uri);
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
     * @param string $uri
     *
     * @return string
     */
    protected function fetch($uri)
    {
        $fileName = md5($uri).'.png';

        $sysPath = $this->cacheSysPath.DIRECTORY_SEPARATOR.$fileName;
        $webPath = $this->cacheWebPath.'/'.$fileName;

        if (!file_exists($sysPath) || time() - filemtime($sysPath) > self::MAX_AGE) {
            file_put_contents($sysPath, file_get_contents($uri));
        }

        return $webPath;
    }
}

/* EOF */
