<?php

/*
 * This file is part of the `src-run/srw-client-silverpapillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

use AppBundle\Twig\Helper\UrlTransformerExtensionHelper;
use SR\WonkaBundle\Twig\Definition\TwigFilterDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

/**
 * Class UrlTransformerExtension.
 */
class UrlTransformerExtension extends TwigExtension
{
    /**
     * @param UrlTransformerExtensionHelper $helper
     */
    public function __construct(UrlTransformerExtensionHelper $helper)
    {
        parent::__construct(new TwigOptionsDefinition(), [
            new TwigFilterDefinition('url_abs_to_rel', [$helper, 'urlAbsToRel']),
        ]);
    }
}

/* EOF */
