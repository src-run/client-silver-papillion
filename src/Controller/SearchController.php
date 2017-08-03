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
        $form = new SearchForm($this->getFormFactory(), $this->getRouter());

        if (null !== $response = $form->handle($request)) {
            return $response;
        }

        return $this->render('AppBundle:search:search.html.twig', [
            'categories' => $this->categoryManager->getAll(),
            'search'     => $form->createFormView(),
            'searchTerm' => urldecode($search),
            'products'   => $this->productManager->getByNameKeywords($search),
        ]);
    }
}
