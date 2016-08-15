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

use AppBundle\Entity\Product;
use AppBundle\Model\Cart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Product ProductController.
 */
class ProductController extends Controller
{
    /**
     * @ParamConverter("product")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($productName, Product $product)
    {
        return $this->render('AppBundle:product:view.html.twig', [
            '_c' => static::class,
            'product' => $product,
            'similar' => $this->get('app.manager.product')->getRandomFromCategory($product->getCategory(), 4),
            'category' => $product->getCategory(),
            'shipping_rate' => Cart::RATE_SHIPPING,
            'tax_rate' => Cart::RATE_TAX_PERCENTAGE,
        ]);
    }
}

/* EOF */
