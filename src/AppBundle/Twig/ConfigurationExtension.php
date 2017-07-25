<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

use AppBundle\Manager\ConfigurationManager;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

/**
 * Class ConfigurationExtension.
 */
class ConfigurationExtension extends TwigExtension
{
    /**
     * @var ConfigurationManager
     */
    private $manager;

    public function __construct()
    {
        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('config_instance', [$this, 'getConfig']),
            new TwigFunctionDefinition('config', [$this, 'getValue']),
        ]);
    }

    /**
     * @param ConfigurationManager $manager
     */
    public function setConfigurationManager(ConfigurationManager $manager)
    {
        $this->manager = $manager;
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
}

/* EOF */
