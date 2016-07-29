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
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;

/**
 * Class ProductRepository.
 */
class ProductRepository extends EntityRepository
{
    /**
     * @return Product[]
     */
    public function findFeatured()
    {
        return $this
            ->createQueryBuilder('p')
            ->where('p.featured = 1')
            ->orderBy('p.name')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Category $category
     *
     * @return Product[]
     */
    public function findInCategory(Category $category)
    {
        return $this
            ->createQueryBuilder('p')
            ->where('p.category = :category')
            ->setParameter('category', $category)
            ->orderBy('p.name')
            ->getQuery()
            ->getResult();
    }
}

/* EOF */
