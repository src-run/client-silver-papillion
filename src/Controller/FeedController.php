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

use Symfony\Component\HttpFoundation\Response;

class FeedController extends AbstractController
{
    /**
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render('AppBundle:feed:index.html.twig');
    }

    /**
     * @return Response
     */
    public function fragmentAction(): Response
    {
        $this->sessionSave();

        return $this->render('AppBundle:feed:feed.html.twig');
    }

    /**
     * @return Response
     */
    public function fragmentPhotosAction(): Response
    {
        $this->sessionSave();

        return $this->render('AppBundle:feed:photos.html.twig');
    }
}
