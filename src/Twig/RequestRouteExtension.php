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

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RequestRouteExtension extends AbstractExtension
{
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('request_controller', function () {
                return $this->getControllerRequestAttribute();
            }),
            new TwigFunction('request_controller_name', function () {
                return $this->getControllerName();
            }),
            new TwigFunction('request_controller_name_short', function () {
                return $this->getControllerNameShort();
            }),
            new TwigFunction('request_controller_action', function () {
                return $this->getControllerAction();
            }),
            new TwigFunction('request_route', function () {
                return $this->getRouteRequestAttribute();
            }),
            new TwigFunction('request_route_parameters', function () {
                return $this->getRouteParamsRequestAttribute();
            }),
        ];
    }

    /**
     * @return string
     */
    private function getControllerName(): ?string
    {
        if (1 === preg_match('{^(?<controller>[^:]+)::}', $this->getControllerRequestAttribute(), $matches)) {
            return $matches['controller'];
        }

        return null;
    }

    /**
     * @return string
     */
    private function getControllerNameShort(): ?string
    {
        if (1 === preg_match('{(?<controller>[^:\\\]+)::}', $this->getControllerRequestAttribute(), $matches)) {
            return $matches['controller'];
        }

        return null;
    }

    /**
     * @return string
     */
    private function getControllerAction(): ?string
    {
        if (1 === preg_match('{::(?<action>.+)$}', $this->getControllerRequestAttribute(), $matches)) {
            return $matches['action'];
        }

        return null;
    }

    /**
     * @return string
     */
    private function getControllerRequestAttribute(): ?string
    {
        return $this->getCurrentRequestAttribute('_controller');
    }

    /**
     * @return string
     */
    private function getRouteRequestAttribute(): ?string
    {
        return $this->getCurrentRequestAttribute('_route');
    }

    /**
     * @return mixed[]
     */
    private function getRouteParamsRequestAttribute(): ?array
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
