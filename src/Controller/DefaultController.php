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

use AppBundle\Entity\Message;
use AppBundle\Form\MessageType;
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
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        return $this->render('AppBundle:default:index.html.twig', [
            '_c' => static::class,
            'categories' => $this->get('app.manager.category')->getAll(),
            'featured' => $this->get('app.manager.product')->getFeatured(),
            'feed' => array_slice($this->get('app.fb.provider.page_feed')->getFeed()->toArray(), 0, 8),
            'form' => $form->createView(),
        ]);
    }
}

/* EOF */
