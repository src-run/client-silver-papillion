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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction()
    {
        return $this->render('AppBundle:cart:view.html.twig', [
            '_c' => static::class,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearAction(Request $request)
    {
        $cart = $this->get('app.cart');
        $cart->clear();
        $cart->save();

        $r = $this->getLastRoute($request);

        if (!isset($r['product'])) {
            return $this->redirect($this->generateUrl($r['_route']));
        }

        return $this->redirect($this->generateUrl($r['_route'], [
            'product' => $r['product'],
            'productName' => $r['productName'],
        ]));
    }

    /**
     * @ParamConverter("product")
     *
     * @param Product $product
     * @param int     $quantity
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Product $product, $quantity, Request $request)
    {
        $cart = $this->get('app.cart');
        foreach (range(1, $quantity) as $i) {
            $cart->add($product);
        }
        $cart->save();

        $r = $this->getLastRoute($request);

        if (!isset($r['product'])) {
            return $this->redirect($this->generateUrl($r['_route']));
        }

        return $this->redirect($this->generateUrl($r['_route'], [
            'product' => $r['product'],
            'productName' => $r['productName'],
        ]));
    }

    /**
     * @ParamConverter("product")
     *
     * @param Product $product
     * @param int     $quantity
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Product $product, $quantity, Request $request)
    {
        $cart = $this->get('app.cart');
        foreach (range(1, $quantity) as $i) {
            $cart->rmOne($product);
        }
        $cart->save();

        $r = $this->getLastRoute($request);

        if (!isset($r['product'])) {
            return $this->redirect($this->generateUrl($r['_route']));
        }

        return $this->redirect($this->generateUrl($r['_route'], [
            'product' => $r['product'],
            'productName' => $r['productName'],
        ]));
    }

    /**
     * @param Request $request
     *
     * @return string[]
     */
    private function getLastRoute(Request $request)
    {
        $referer = $request->headers->get('referer');
        $lastUrl = parse_url($referer, PHP_URL_PATH);

        return $this->get('router')->getMatcher()->match($lastUrl);
    }
}

/* EOF */
