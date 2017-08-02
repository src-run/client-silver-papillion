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

use AppBundle\Form\SearchType;
use AppBundle\Model\Search;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends AbstractProductAwareController
{
    /**
     * @param Request  $request
     * @param string   $search
     *
     * @return Response
     */
    public function searchAction(Request $request, string $search): Response
    {
        $search = urldecode($search);

        $form = $this->createForm(SearchType::class, $formSearch = new Search());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectRouteTemporary('app_search', [
                'search' => urlencode($formSearch->getSearch()),
            ]);
        }

        return $this->render('AppBundle:search:search.html.twig', [
            '_c'         => static::class,
            'categories' => $this->categoryManager->getAll(),
            'search'     => $form->createView(),
            'searchTerm' => $search,
            'products'   => $this->productManager->getByNameSearch($search),
        ]);
    }
}
