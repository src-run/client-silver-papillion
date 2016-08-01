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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Product ProductController.
 */
class ProductController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        return $this->render('AppBundle:product:list.html.twig', [
            '_c' => static::class,
            'products' => $this->get('app.manager.product')->getAll()
        ]);
    }

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
            'categories' => $this->get('app.manager.category')->getAll(),
        ]);
    }
}

/* EOF */
