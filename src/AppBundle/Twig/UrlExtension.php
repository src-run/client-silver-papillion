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

use AppBundle\Component\Environment\Environment;
use SR\Exception\Logic\InvalidArgumentException;
use SR\Exception\Logic\LogicException;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\VarDumper\VarDumper;

class UrlExtension extends TwigExtension
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Environment
     */
    private $environment;

    /**
     * @var string[]
     */
    private $domainTypes;

    /**
     * @param RouterInterface $router
     * @param Environment     $environment
     * @param string[]        $domainTypes
     */
    public function __construct(RouterInterface $router, Environment $environment, array $domainTypes = [])
    {
        $this->router = $router;
        $this->environment = $environment;
        $this->domainTypes = $domainTypes;

        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('ajax_data_href', [$this, 'getAjaxDataHref'], new TwigOptionsDefinition(['is_safe' => ['html']])),
        ]);
    }

    public function getAjaxDataHref(string $route, ...$parameters)
    {
        return call_user_func_array([$this, $this->getMethodName($type = $this->getUrlType(__FUNCTION__))], [
            $type,
            $route,
            $parameters,
        ]);
    }

    /**
     * @param string $method
     *
     * @return string
     */
    private function getUrlType(string $method): string
    {
        if (1 !== preg_match('{get(?<type>[A-Z][a-z]+)}i', $method, $matches)) {
            throw new InvalidArgumentException('Invalid method argument provided "%s"', $method);
        }

        return $matches['type'];
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function getMethodName(string $type): string
    {
        if (!method_exists($this, $name = sprintf('compile%sUrl', $type))) {
            throw new LogicException('Invalid method type provided "%s"', $type);
        }

        return $name;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function getTemplate(string $type): string
    {
        if (!array_key_exists($type, $this->domainTypes) || !array_key_exists('template', $this->domainTypes[$type])) {
            throw new LogicException('Invalid domain type or missing template for "%s"', $type);
        }

        return $this->domainTypes[$type]['template'];
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function getDomain(string $type): string
    {
        if (!array_key_exists($type, $this->domainTypes) || !array_key_exists('domain', $this->domainTypes[$type])) {
            throw new LogicException('Invalid domain type or missing domain for "%s"', $type);
        }

        return $this->domainTypes[$type]['domain'];
    }

    /**
     * @param string $route
     * @param array  $parameters
     *
     * @return string
     */
    private function generateNetworkPathRoute(string $route, array $parameters): string
    {
        return $this->router->generate($route, $parameters, RouterInterface::NETWORK_PATH);
    }

    /**
     * @param string $route
     * @param array  $parameters
     *
     * @return string
     */
    private function generateAbsolutePathRoute(string $route, array $parameters): string
    {
        return $this->router->generate($route, $parameters, RouterInterface::ABSOLUTE_PATH);
    }

    /**
     * @param string $type
     * @param string $route
     * @param array  $parameters
     *
     * @return string
     */
    private function compileAjaxDataHrefUrl(string $type, string $route, array $parameters): string
    {
        if ($this->environment->isDevelopment()) {
            return sprintf($this->getTemplate($type), $this->generateNetworkPathRoute($route, $parameters));
        }

        return vsprintf($this->getTemplate($type), [
            sprintf('//%s%s', $this->getDomain($type), $this->generateAbsolutePathRoute($route, $parameters)),
        ]);
    }
}

/* EOF */
