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
 * Class LegalController.
 */
class LegalController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tosAction()
    {
        return $this->render('AppBundle:legal:tos.html.twig', [
            '_c' => static::class,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function privacyAction()
    {
        return $this->render('AppBundle:legal:privacy.html.twig', [
            '_c' => static::class,
        ]);
    }
}

/* EOF */
