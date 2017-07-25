<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig\Helper;

use SR\Exception\Logic\InvalidArgumentException;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class FrameworkReflectExtensionHelper.
 */
class FrameworkReflectExtensionHelper
{
    /**
     * @var string
     */
    private $environment;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @param string $environment
     * @param bool   $debug
     */
    public function __construct(string $environment, bool $debug)
    {
        $this->environment = $environment;
        $this->debug = $debug;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return (bool) $this->debug;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'symfony/symfony';
    }

    /**
     * @return int
     */
    public function getKernelId()
    {
        return Kernel::VERSION_ID;
    }

    /**
     * @return string
     */
    public function getEndOfLife()
    {
        return preg_replace('{[^0-9]+}i', '', Kernel::END_OF_LIFE);
    }

    /**
     * @return string
     */
    public function frameworkInfo($what)
    {
        switch (strtolower($what)) {
            case 'env':
                return $this->environment;

            case 'debug':
                return $this->debug === true ? 'true' : 'false';

            case 'name':
                return 'symfony/symfony';

            case 'version':
                return Kernel::VERSION_ID;

            case 'eol':
                return preg_replace('{[^0-9]+}i', '', Kernel::END_OF_LIFE);

            default:
                throw InvalidArgumentException::create('Invalid framework property requested '.$what);
        }
    }
}

/* EOF */
