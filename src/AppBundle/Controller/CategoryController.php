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

use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Category CategoryController.
 */
class CategoryController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        return $this->render('AppBundle:category:list.html.twig', [
            '_c' => static::class,
            'categories' => $this->get('app.manager.category')->getAll()
        ]);
    }

    /**
     * @ParamConverter("category")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request, $categoryName, Category $category)
    {
        return $this->render('AppBundle:category:view.html.twig', [
            '_c' => static::class,
            'category' => $category,
            'categories' => $this->get('app.manager.category')->getAll(),
            'pagination' => $this->get('app.manager.product')->getAllFromCategoryPaginated($category, $request->query->getInt('page', 1)),
        ]);
    }
}

/* EOF */
