<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use Doctrine\ORM\QueryBuilder;

/**
 * Class CategoryRepository.
 */
class CategoryRepository extends AbstractRepository
{
    /**
     * @return Category[]
     */
    public function findAllEnabled()
    {
        return $this->findAllOrderByWeight();
    }

    /**
     * @return Category[]
     */
    public function findAllOrderByWeight()
    {
        return $this->getResult(function (QueryBuilder $b) {
            $b
                ->andWhere('c.enabled = 1')
                ->orderBy('c.weight');
        });
    }
}

/* EOF */
