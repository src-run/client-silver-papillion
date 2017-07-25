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

use SR\WonkaBundle\Twig\Definition\TwigFilterDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

/**
 * Class StripHtmlExtension.
 */
class StripHtmlExtension extends TwigExtension
{
    public function __construct()
    {
        parent::__construct(new TwigOptionsDefinition(), [
            new TwigFilterDefinition('strip_html', function (string $html, string $allowed = '<b><string><em><italic><a>') {
                return strip_tags($html, $allowed);
            })
        ], []);
    }
}

/* EOF */
