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
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CarouselSlideExtension extends AbstractExtension
{
    /**
     * @var CarouselSlideManager
     */
    private $carouselSlideManager;

    /**
     * @param CarouselSlideManager $carouselSlideManager
     */
    public function __construct(CarouselSlideManager $carouselSlideManager)
    {
        $this->carouselSlideManager = $carouselSlideManager;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('carousel_slides', function (): array {
                return $this->carouselSlideManager->getEnabled();
            }, ['is_safe' => ['html']]),
        ];
    }
}
