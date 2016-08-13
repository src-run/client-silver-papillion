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
        return $this->render('AppBundle:default:index.html.twig', [
            '_c' => static::class,
            'hours' => $this->get('app.manager.hours')->getAll(),
            'featured' => $this->get('app.manager.product')->getFeatured(),
            'feed' => $this->get('app.fb.provider.page_feed')->getFeed(),
            'staticMaps' => $this->get('app.mapper.static')->generate('420x220'),
        ]);
    }
}

/* EOF */
