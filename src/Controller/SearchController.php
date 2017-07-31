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
use AppBundle\Form\SearchType;
use AppBundle\Model\Search;
use AppBundle\Model\SearchProduct;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

class SearchController extends AbstractProductAwareController
{
    /**
     * @param Request  $request
     * @param string   $search
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request, string $search)
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

/* EOF */
