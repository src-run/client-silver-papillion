<?php

/*
 * This file is part of the `src-run/srw-client-silverpapillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Manager;

use AppBundle\Entity\CarouselSlide;
use AppBundle\Repository\CarouselSlideRepository;

/**
 * Class CarouselSlideManager.
 */
class CarouselSlideManager extends AbstractManager
{
    /**
     * @var string
     */
    const ENTITY = CarouselSlide::class;

    /**
     * @return CarouselSlideRepository|\Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository(static::ENTITY);
    }

    /**
     * @return CarouselSlide[]
     */
    public function getEnabled()
    {
        return $this->getRepository()->findAllEnabledOrderedByWeight();
    }
}

/* EOF */
