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
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

/**
 * Class FrameworkReflectTwigExtension.
 */
class FrameworkReflectTwigExtension extends TwigExtension
{
    /**
     * @param FrameworkReflectExtensionHelper $helper
     */
    public function __construct(FrameworkReflectExtensionHelper $helper)
    {
        $options = new TwigOptionsDefinition(['is_safe' => ['html']]);

        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('framework_env', [$helper, 'getEnvironment'], $options),
            new TwigFunctionDefinition('framework_debug', [$helper, 'isDebug'], $options),
            new TwigFunctionDefinition('framework_name', [$helper, 'getName'], $options),
            new TwigFunctionDefinition('framework_version', [$helper, 'getKernelId'], $options),
            new TwigFunctionDefinition('framework_eol', [$helper, 'getEndOfLife'], $options),
        ]);
    }
}

/* EOF */
