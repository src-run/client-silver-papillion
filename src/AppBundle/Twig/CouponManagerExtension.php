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
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

class CouponManagerExtension extends TwigExtension
{
    /**
     * @var CouponManager
     */
    private $manager;

    public function __construct()
    {
        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('get_featured_coupons', function (int $count = 1) {
                return $this->manager->getFeatured($count);
            }),
            new TwigFunctionDefinition('get_published_coupons', function (int $count = 1000) {
                return $this->manager->getPublished($count);
            })
        ]);
    }

    /**
     * @param CouponManager $manager
     */
    public function setCouponManager(CouponManager $manager)
    {
        $this->manager = $manager;
    }
}

/* EOF */
