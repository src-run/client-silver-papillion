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
use Symfony\Component\VarDumper\VarDumper;

/**
 * Category FeedController.
 */
class FeedController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('AppBundle:feed:index.html.twig', [
            '_c' => static::class,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fragmentAction()
    {
        $this->get('session')->save();

        return $this->render('AppBundle:feed:feed.html.twig', [
            '_c' => static::class,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fragmentPhotosAction()
    {
        $this->get('session')->save();

        return $this->render('AppBundle:feed:photos.html.twig', [
            '_c' => static::class,
        ]);
    }
}

/* EOF */
