<?php

/*
 * This file is part of the `src-run/srw-client-silverpapillon` project.
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
        $count = $this->get('app.manager.configuration')->value('product.count.similar', 4);

        return $this->render('AppBundle:product:view.html.twig', [
            '_c'       => static::class,
            'product'  => $product,
            'similar'  => $this->get('app.manager.product')->getRandomFromCategory($product->getCategory(), $count),
            'category' => $product->getCategory(),
        ]);
    }
}

/* EOF */
