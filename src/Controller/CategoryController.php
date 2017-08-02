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

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Form\SearchType;
use AppBundle\Model\Search;
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
        $form = $this->createForm(SearchType::class, $search = new Search());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectRouteTemporary('app_search', [
                'search' => urlencode($search->getSearch()),
            ]);
        }

        return $this->render('AppBundle:category:list.html.twig', [
            '_c'         => static::class,
            'categories' => $this->categoryManager->getAllByWeight(),
            'search'     => $form->createView(),
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
        $form = $this->createForm(SearchType::class, $search = new Search());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectRouteTemporary('app_search', [
                'search' => urlencode($search->getSearch()),
            ]);
        }

        $page = $request->query->getInt('p', 1);
        $count = $this->configurationValue('product.count', 12);

        return $this->render('AppBundle:category:view.html.twig', [
            '_c'         => static::class,
            'category'   => $category,
            'categories' => $this->categoryManager->getAll(),
            'pagination' => $this->productManager->getAllFromCategoryPaginated($category, $page, $count),
            'search'     => $form->createView(),
        ]);
    }
}
