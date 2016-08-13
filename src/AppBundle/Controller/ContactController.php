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

use AppBundle\Form\MessageType;
use AppBundle\Model\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ContactController.
 */
class ContactController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->sendMessage($message);

            return $this->render('AppBundle:contact:sent.html.twig', [
                '_c' => static::class
            ]);
        }

        return $this->render('AppBundle:contact:index.html.twig', [
            '_c' => static::class,
            'form' => $form->createView(),
        ]);
    }

    protected function sendMessage(Message $message)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Website message from '.$message->getName())
            ->setFrom('no-reply@src.run')
            ->setTo('robfrawley@gmail.com')
            ->setReplyTo($message->getEmail())
            ->setBody(
                $this->renderView(
                    'email/message.html.twig',
                    [
                        'name' => $message->getName(),
                        'message' => $message->getMessage(),
                        'email' => $message->getEmail(),
                    ]
                ),
                'text/html'
            );

        $this->get('mailer')->send($message);
    }
}

/* EOF */
