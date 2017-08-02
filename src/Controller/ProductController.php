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
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractProductAwareController
{
    /**
     * @ParamConverter("product")
     *
     * @param string  $productName
     * @param Product $product
     *
     * @return Response
     */
    public function viewAction(string $productName, Product $product): Response
    {
        return $this->render('AppBundle:product:view.html.twig', [
            '_c'       => static::class,
            'product'  => $product,
            'similar'  => $this->productManager->getRandomSimilar(
                $product,
                $this->configurationValue('product.count.similar', 8)
            ),
            'related'  => $this->productManager->getRelated($product),
            'other'    => $this->productManager->getRandomOther(
                $product,
                $this->configurationValue('product.count.other', 8)
            ),
            'category' => $product->getCategory(),
        ]);
    }
}
