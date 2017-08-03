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

use AppBundle\Component\Form\SearchForm;
use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractProductAwareController
{
    /**
     * @ParamConverter("product")
     *
     * @param Product $product
     * @param Request $request
     *
     * @return Response
     */
    public function viewAction(Product $product, Request $request): Response
    {
        $form = new SearchForm($this->getFormFactory(), $this->getRouter());

        if (null !== $response = $form->handle($request)) {
            return $response;
        }

        return $this->render('AppBundle:product:view.html.twig', [
            'search'   => $form->createFormView(),
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
