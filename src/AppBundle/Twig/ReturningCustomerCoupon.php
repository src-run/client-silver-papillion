<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

use AppBundle\Entity\Coupon;
use AppBundle\Repository\CouponRepository;
use Doctrine\ORM\EntityManager;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

class ReturningCustomerCoupon extends TwigExtension
{
    /**
     * @var string
     */
    private static $couponName = 'Order Email Offer';

    /**
     * @var CouponRepository
     */
    private $repository;

    /**
     * @var Coupon
     */
    private $coupon;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository(Coupon::class);

        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('has_returning_customer_coupon', function () {
                return $this->getCoupon() !== null;
            }),
            new TwigFunctionDefinition('returning_customer_coupon', function () {
                return $this->getCoupon();
            }),
        ]);
    }

    /**
     * @return Coupon|null
     */
    private function getCoupon(): ?Coupon
    {
        if ($this->coupon !== null) {
            return $this->coupon;
        }

        try {
            $coupon = $this->repository->findOneBy([
                'name' => static::$couponName,
            ]);

            if (!$coupon) {
                return null;
            }

            $this->coupon = $coupon->isEnabled() ? $coupon : null;
        }
        catch (\Exception $e) {
            return null;
        }

        return $this->coupon;
    }
}
