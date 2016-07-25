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
use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController.
 */
class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $manager = $this->getDoctrine()->getManager();

        $categoriesRepo = $manager->getRepository(Category::class);
        $categories = $categoriesRepo->findAll();

        $productsRepo = $manager->getRepository(Product::class);
        $featured = $productsRepo->findFeatured();

        return $this->render('AppBundle:default:index.html.twig', [
            'categories' => $categories,
            'featured' => $featured,
        ]);
    }
}

/* EOF */
