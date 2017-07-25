<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CouponController extends Controller
{
    /**
     * @ParamConverter("product")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $couponManager = $this->get('app.manager.coupon');

        return $this->render('AppBundle:coupon:list.html.twig', [
            '_c'      => static::class,
            'coupons' => $couponManager->getPublished()
        ]);
    }
}

/* EOF */
