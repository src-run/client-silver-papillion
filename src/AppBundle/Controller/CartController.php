<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Category CartController.
 */
class CartController extends Controller
{
    /**
     * @ParamConverter("product")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Product $product, $quantity, Request $request)
    {
        $r = $this->getLastRoute($request);

        return $this->redirect($this->generateUrl($r['_route'], [
            'product' => $r['product'],
            'productName' => $r['productName'],
        ]));
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    private function getLastRoute(Request $request)
    {
        $referer = $request->headers->get('referer');
        $lastUrl = parse_url($referer, PHP_URL_PATH);

        return $this->get('router')->getMatcher()->match($lastUrl);
    }
}

/* EOF */
