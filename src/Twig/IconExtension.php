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

use AppBundle\Twig\Helper\IconExtensionHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class IconExtension extends AbstractExtension
{
    /**
     * @var IconExtensionHelper
     */
    private $helper;

    /**
     * @param IconExtensionHelper $helper
     */
    public function __construct(IconExtensionHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('ion', function ($icon) {
                return $this->helper->renderIconIon($icon, false);
            }, ['is_safe' => ['html']]),
            new TwigFunction('ion-block', function ($icon) {
                return $this->helper->renderIconIon($icon, true);
            }, ['is_safe' => ['html']]),
            new TwigFunction('fa', function ($icon) {
                return $this->helper->renderIconFa($icon, false);
            }, ['is_safe' => ['html']]),
            new TwigFunction('fa-block', function ($icon) {
                return $this->helper->renderIconFa($icon, true);
            }, ['is_safe' => ['html']]),
        ];
    }
}
