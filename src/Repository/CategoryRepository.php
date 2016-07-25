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

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;

/**
 * Class CategoryRepository.
 */
class CategoryRepository extends EntityRepository
{
    /**
     * @return Category[]
     */
    public function findAll()
    {
        return $this
            ->createQueryBuilder('p')
            ->getQuery()
            ->getResult();
    }
}

/* EOF */
