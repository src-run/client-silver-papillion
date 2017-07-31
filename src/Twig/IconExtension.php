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
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

/**
 * Class IconExtension.
 */
class IconExtension extends TwigExtension
{
    /**
     * @param IconExtensionHelper $helper
     */
    public function __construct(IconExtensionHelper $helper)
    {
        $options = new TwigOptionsDefinition(['is_safe' => ['html']]);

        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('ion', function ($icon) use ($helper) {
                return $helper->renderIconIon($icon, false);
            }, $options),
            new TwigFunctionDefinition('ion-block', function ($icon) use ($helper) {
                return $helper->renderIconIon($icon, true);
            }, $options),
            new TwigFunctionDefinition('fa', function ($icon) use ($helper) {
                return $helper->renderIconFa($icon, false);
            }, $options),
            new TwigFunctionDefinition('fa-block', function ($icon) use ($helper) {
                return $helper->renderIconFa($icon, true);
            }, $options),
        ]);
    }
}

/* EOF */
