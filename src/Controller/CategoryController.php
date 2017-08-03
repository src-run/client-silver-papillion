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
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractProductAwareController
{
    /**
     * @param Request  $request
     *
     * @return Response
     */
    public function listAction(Request $request): Response
    {
        $form = new SearchForm($this->getFormFactory(), $this->getRouter());

        if (null !== $response = $form->handle($request)) {
            return $response;
        }

        return $this->render('AppBundle:category:list.html.twig', [
            'search'     => $form->createFormView(),
            'categories' => $this->categoryManager->getAllByWeight(),
        ]);
    }

    /**
     * @ParamConverter("category", options={"mapping": {"category": "slug"}})
     *
     * @throws \Exception
     *
     * @param Request  $request
     * @param Category $category
     *
     * @return Response
     */
    public function viewAction(Request $request, Category $category): Response
    {
        $form = new SearchForm($this->getFormFactory(), $this->getRouter());

        if (null !== $response = $form->handle($request)) {
            return $response;
        }

        return $this->render('AppBundle:category:view.html.twig', [
            'search'     => $form->createFormView(),
            'category'   => $category,
            'products'   => $this->productManager->getAllFromCategoryPaginated(
                $category,
                $request->query->getInt('p', 1),
                $this->configurationValue('product.count', 12)
            ),
        ]);
    }
}
