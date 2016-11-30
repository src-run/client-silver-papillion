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

use AppBundle\Util\Slugger;
use SR\WonkaBundle\Twig\Definition\TwigFilterDefinition;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

/**
 * Class SluggerExtension.
 */
class SluggerExtension extends TwigExtension
{
    /**
     * @param Slugger $slugger
     */
    public function __construct(Slugger $slugger)
    {
        parent::__construct(new TwigOptionsDefinition(), [
            new TwigFilterDefinition('slugify', [$slugger, 'slugify']),
        ], [
            new TwigFunctionDefinition('slugify', [$slugger, 'slugify']),
        ]);
    }
}

/* EOF */
