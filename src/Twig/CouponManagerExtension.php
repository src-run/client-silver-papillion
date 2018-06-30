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

use AppBundle\Manager\CouponManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CouponManagerExtension extends AbstractExtension
{
    /**
     * @var CouponManager
     */
    private $manager;

    /**
     * CouponManagerExtension constructor.
     *
     * @param CouponManager $manager
     */
    public function __construct(CouponManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_featured_coupon', function () {
                return $this->manager->getFeatured();
            }),
            new TwigFunction('has_featured_coupon', function () {
                return $this->manager->getFeatured() !== null;
            }),
            new TwigFunction('get_published_coupons', function (int $count = 1000) {
                return $this->manager->getPublished($count);
            })
        ];
    }
}
