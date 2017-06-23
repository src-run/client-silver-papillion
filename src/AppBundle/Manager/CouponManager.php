<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Coupon;
use AppBundle\Repository\CouponRepository;

class CouponManager extends AbstractManager
{
    /**
     * @var string
     */
    const ENTITY = Coupon::class;

    /**
     * @return CouponRepository|\Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository(static::ENTITY);
    }

    /**
     * @param int $limit
     *
     * @return Coupon[]
     */
    public function getFeatured($limit = 3)
    {
        return array_slice($this->getRepository()->findFeatured(), 0, $limit);
    }

    /**
     * @param int $limit
     *
     * @return Coupon[]
     */
    public function getPublished($limit = 3)
    {
        return array_slice($this->getRepository()->findPublished(), 0, $limit);
    }

    /**
     * @return Coupon
     */
    public function getFeaturedRandom()
    {
        $coupons = $this->getFeatured(10000);
        shuffle($coupons);

        return array_pop($coupons);
    }
}

/* EOF */
