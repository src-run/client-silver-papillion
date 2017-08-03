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

use AppBundle\Entity\Coupon;
use Doctrine\ORM\QueryBuilder;

class CouponRepository extends AbstractRepository
{
    /**
     * @return Coupon[]
     */
    public function findFeatured()
    {
        return $this->getResult(function (QueryBuilder $queryBuilder) {
            $queryBuilder
                ->where('c.enabled = 1')
                ->andWhere('c.featured = 1')
                ->orderBy('c.expiration');
        });
    }

    /**
     * @return Coupon[]
     */
    public function findPublished()
    {
        return $this->getResult(function (QueryBuilder $queryBuilder) {
            $queryBuilder
                ->where('c.enabled = 1')
                ->andWhere('c.published = 1')
                ->orderBy('c.expiration');
        });
    }

    /**
     * @param string $name
     *
     * @return Coupon
     */
    public function findSingleByName(string $name): ?Coupon
    {
        return $this->getResult(function (QueryBuilder $queryBuilder) use ($name) {
            $queryBuilder
                ->where('c.name = :name')
                ->setParameter('name', $name)
                ->setMaxResults(1);
        }, true);
    }
}

/* EOF */
