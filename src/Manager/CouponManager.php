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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CouponManager extends AbstractManager
{
    /**
     * @var string
     */
    const ENTITY = Coupon::class;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @param EntityManagerInterface $em
     * @param SessionInterface       $session
     */
    public function __construct(EntityManagerInterface $em, SessionInterface $session)
    {
        parent::__construct($em);

        $this->session = $session;
    }

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
     * @return Coupon[]|Coupon
     */
    public function getFeatured($limit = 1)
    {
        $coupons = $this->getRepository()->findFeatured();

        if (count($coupons) === 0) {
            return null;
        }

        if ($limit === 1) {
            shuffle($coupons);
            return array_shift($coupons);
        }

        return array_slice($coupons, 0, $limit);
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

    /**
     * @return bool
     */
    public function getCouponViewedState()
    {
        $now = time();

        if (($this->session->get('coupon_featured') ?? $now) <= $now) {
            $this->session->set('coupon_featured', strtotime('+10 minute'));

            return false;
        }

        return true;
    }

    /**
     * @param string $name
     *
     * @return Coupon|null
     */
    public function getByName(string $name): ?Coupon
    {
        return $this->getRepository()->findSingleByName($name);
    }
}

/* EOF */
