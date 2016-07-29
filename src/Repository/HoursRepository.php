<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Hours;
use Doctrine\ORM\EntityRepository;

/**
 * Class HoursRepository.
 */
class HoursRepository extends EntityRepository
{
    /**
     * @return Hours[]
     */
    public function findAll()
    {
        return $this
            ->createQueryBuilder('h')
            ->orderBy('h.weight')
            ->getQuery()
            ->getResult();
    }
}

/* EOF */
