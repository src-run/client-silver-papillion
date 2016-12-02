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

use AppBundle\Manager\CarouselSlideManager;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

/**
 * Class CarouselSlideExtension.
 */
class CarouselSlideExtension extends TwigExtension
{
    /**
     * @param CarouselSlideManager $manager
     */
    public function __construct(CarouselSlideManager $manager)
    {
        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('carousel_slides', [$manager, 'getEnabled'], new TwigOptionsDefinition(['is_safe' => ['html']])),
        ]);
    }
}

/* EOF */
