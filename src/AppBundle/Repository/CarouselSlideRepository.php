<?php

/*
 * This file is part of the `src-run/srw-client-silverpapillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;

use AppBundle\Entity\CarouselSlide;
use Doctrine\ORM\QueryBuilder;

/**
 * Class CarouselSlideRepository.
 */
class CarouselSlideRepository extends AbstractRepository
{
    /**
     * @return CarouselSlide[]
     */
    public function findAllEnabledOrderedByWeight()
    {
        return $this->getResult(function (QueryBuilder $b) {
            $b
                ->where('c.enabled = 1')
                ->orderBy('c.weight');
        });
    }
}

/* EOF */
