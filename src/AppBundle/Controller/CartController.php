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
use AppBundle\Form\ShipmentType;
use AppBundle\Model\Shipment;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkoutAction(Request $request)
    {
        $shipment = new Shipment();
        $form = $this->createForm(ShipmentType::class, $shipment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($form);
        }

        return $this->render('AppBundle:cart:checkout.html.twig', [
            '_c' => static::class,
            'f' => $form->createView(),
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

        return $this->redirectBack($r);
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

        return $this->redirectBack($r);
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

        return $this->redirectBack($r);
    }

    /**
     * @ParamConverter("product")
     *
     * @param Product $product
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeGroupAction(Product $product, Request $request)
    {
        $cart = $this->get('app.cart');
        $cart->rm($product);
        $cart->save();

        $r = $this->getLastRoute($request);

        if (!isset($r['product'])) {
            return $this->redirect($this->generateUrl($r['_route']));
        }

        return $this->redirectBack($r);
    }

    /**
     * @param string[] $r
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectBack($r)
    {
        return $this->redirect($this->generateUrl($r['_route'], [
            'category' => $r['category'],
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
