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
use AppBundle\Manager\CouponManager;
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
     * @var CouponManager
     */
    private $manager;

    /**
     * @var Coupon
     */
    private $coupon;

    /**
     * @param CouponManager $manager
     */
    public function __construct(CouponManager $manager)
    {
        $this->manager = $manager;

        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('has_returning_customer_coupon', function () {
                return $this->hasCoupon();
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
        if ($this->hasCoupon()) {
            return $this->coupon;
        }

        try {
            if (null === $coupon = $this->manager->getByName(static::$couponName)) {
                return null;
            }

            $this->coupon = $coupon->isEnabled() ? $coupon : null;
        } catch (\Exception $e) {
            return null;
        }

        return $this->coupon;
    }

    /**
     * @return bool
     */
    private function hasCoupon(): bool
    {
        return null !== $this->coupon;
    }
}
