<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

use SR\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class FrameworkReflectExtension.
 */
class FrameworkReflectExtension extends \Twig_Extension
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
    public function __construct($environment, $debug)
    {
        $this->environment = $environment;
        $this->debug = $debug;
    }

    /**
     * @return \Twig_Function[]
     */
    public function getFunctions()
    {
        return array(
            new \Twig_Function('framework_*', [$this, 'frameworkInfo']),
        );
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

    /**
     * @return string
     */
    public function getName()
    {
        return 'framework_reflect_extension';
    }
}

/* EOF */
