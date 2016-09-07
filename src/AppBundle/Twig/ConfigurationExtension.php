<?php

/*
 * This file is part of the `src-run/srw-client-silverpapillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

use AppBundle\Manager\ConfigurationManager;

/**
 * Class ConfigurationExtension.
 */
class ConfigurationExtension extends \Twig_Extension
{
    /**
     * @var ConfigurationManager
     */
    private $manager;

    /**
     * @param ConfigurationManager $manager
     */
    public function setConfigurationManager(ConfigurationManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return \Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('config_instance', [$this, 'getConfig']),
            new \Twig_Function('config', [$this, 'getValue']),
        ];
    }

    /**
     * @param string $index
     *
     * @return \AppBundle\Entity\Configuration
     */
    public function getConfig($index)
    {
        return $this->manager->get($index);
    }

    /**
     * @param string     $index
     * @param mixed|null $default
     *
     * @return string
     */
    public function getValue($index, $default = null)
    {
        return $this->manager->value($index, $default);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'configuration_extension';
    }
}

/* EOF */
