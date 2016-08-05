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
 * Class ContactController.
 */
class ContactController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        return $this->render('AppBundle:contact:index.html.twig', [
            '_c' => static::class,
            'categories' => $this->get('app.manager.category')->getAll(),
            'feed' => $this->get('app.fb.provider.page_feed')->getFeed(),
            'form' => $form->createView(),
        ]);
    }
}

/* EOF */
