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
        $q = $this
            ->createQueryBuilder('p')
            ->where('p.featured = 1')
            ->orderBy('p.name')
            ->setMaxResults(3)
            ->getQuery();

        return $q->getResult();
    }

    public function findInCategory(Category $category)
    {
        $q = $this
            ->createQueryBuilder('p')
            ->where('p.category = :category')
            ->setParameter('category', $category)
            ->orderBy('p.name')
            ->getQuery();

        return $q->getResult();
    }
}

/* EOF */
