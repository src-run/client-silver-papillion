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

use SR\Exception\Runtime\RuntimeException;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\VarDumper\VarDumper;

class RequestRouteExtension extends TwigExtension
{
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;

        parent::__construct(null, [], [
            new TwigFunctionDefinition('request_controller', function () {
                return $this->getController();
            }),
            new TwigFunctionDefinition('request_controller_name', function () {
                return $this->getControllerName();
            }),
            new TwigFunctionDefinition('request_controller_name_short', function () {
                return $this->getControllerNameShort();
            }),
            new TwigFunctionDefinition('request_controller_action', function () {
                return $this->getControllerAction();
            }),
            new TwigFunctionDefinition('request_route', function () {
                return $this->getRoute();
            }),
            new TwigFunctionDefinition('request_route_parameters', function () {
                return $this->getRouteParameters();
            }),
        ]);
    }

    /**
     * @return string
     */
    private function getController(): ?string
    {
        return $this->getCurrentRequestAttribute('_controller');
    }

    /**
     * @return string
     */
    private function getControllerName(): ?string
    {
        if (1 === preg_match('{^(?<controller>[^:]+)::}', $this->getController(), $matches)) {
            return $matches['controller'];
        }

        return null;
    }

    /**
     * @return string
     */
    private function getControllerNameShort(): ?string
    {
        if (1 === preg_match('{(?<controller>[^:\\\]+)::}', $this->getController(), $matches)) {
            return $matches['controller'];
        }

        return null;
    }

    /**
     * @return string
     */
    private function getControllerAction(): ?string
    {
        if (1 === preg_match('{::(?<action>.+)$}', $this->getController(), $matches)) {
            return $matches['action'];
        }

        return null;
    }

    /**
     * @return string
     */
    private function getRoute(): ?string
    {
        return $this->getCurrentRequestAttribute('_route');
    }

    /**
     * @return mixed[]
     */
    private function getRouteParameters(): ?array
    {
        return $this->getCurrentRequestAttribute('_route_params');
    }

    /**
     * @param string $name
     *
     * @return null|string|array
     */
    private function getCurrentRequestAttribute(string $name)
    {
        $attributes = $this->requestStack->getCurrentRequest()->attributes;

        return $attributes->has($name) ? $attributes->get($name) : null;
    }
}

/* EOF */
