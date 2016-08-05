<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

use AppBundle\Entity\CarouselSlide;
use AppBundle\Manager\CarouselSlideManager;

/**
 * Class CarouselSlideExtension
 */
class CarouselSlideExtension extends \Twig_Extension
{
    /**
     * @var CarouselSlideManager
     */
    private $manager;

    /**
     * @param CarouselSlideManager $manager
     */
    public function setCarouselSlideManager(CarouselSlideManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return \Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('carousel_slides', [$this, 'getAllCarouselSlides'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @return CarouselSlide[]
     */
    public function getAllCarouselSlides()
    {
        return $this->manager->getEnabled();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'carousel_slide_extension';
    }
}

/* EOF */
