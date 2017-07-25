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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class LocationController.
 */
class LocationController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('AppBundle:location:index.html.twig', [
            '_c'            => static::class,
            'hours'         => $this->get('app.manager.hours')->getAll(),
            'staticMaps'    => $this->get('app.mapper.static')->generate(),
            'streetView'    => $this->get('app.mapper.street')->generate(),
            'directionsUri' => $this->get('app.directions')->generate(),
        ]);
    }
}

/* EOF */
