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

use AppBundle\Twig\Helper\FrameworkReflectExtensionHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FrameworkReflectTwigExtension extends AbstractExtension
{
    /**
     * @var FrameworkReflectExtensionHelper
     */
    private $helper;

    /**
     * @param FrameworkReflectExtensionHelper $helper
     */
    public function __construct(FrameworkReflectExtensionHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('framework_env', function (): string {
                return $this->helper->getEnvironment();
            }, ['is_safe' => ['html']]),
            new TwigFunction('framework_debug', function (): bool {
                return $this->helper->isDebug();
            }, ['is_safe' => ['html']]),
            new TwigFunction('framework_name', function (): string {
                return $this->helper->getName();
            }, ['is_safe' => ['html']]),
            new TwigFunction('framework_version', function (): int {
                return $this->helper->getKernelId();
            }, ['is_safe' => ['html']]),
            new TwigFunction('framework_eol', function (): string {
                return $this->helper->getEndOfLife();
            }, ['is_safe' => ['html']]),
        ];
    }
}
