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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Category DefaultController.
 */
class CategoryController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Category::class);
        $categories = $repo->findAll();

        return $this->render('AppBundle:default:index.html.twig', ['categories' => $categories]);
    }
}

/* EOF */
