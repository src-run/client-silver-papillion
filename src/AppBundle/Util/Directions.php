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
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class Directions.
 */
class Directions implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var ConfigurationManager
     */
    protected $configurationManager;

    /**
     * @var string
     */
    protected $uri;

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
     * @param string $size
     *
     * @return string
     */
    public function generate()
    {
        return $this->getUriCompiled();
    }

    /**
     * @return string
     */
    protected function getAddress()
    {
        return urlencode(strip_tags(str_replace("\r\n", '+', $this->configurationManager->value('address'))));
    }

    /**
     * @param string $size
     *
     * @return mixed|string
     */
    protected function getUriCompiled()
    {
        return str_replace('${address}', $this->getAddress(), $this->uri);
    }
}

/* EOF */
