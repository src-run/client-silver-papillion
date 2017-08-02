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

use AppBundle\Form\MessageType;
use AppBundle\Model\Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractController
{
    /**
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->sendMessage($message);

            return $this->render('AppBundle:contact:sent.html.twig', [
                '_c' => static::class,
            ]);
        }

        return $this->render('AppBundle:contact:index.html.twig', [
            '_c'   => static::class,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Message $message
     */
    protected function sendMessage(Message $message): void
    {
        $config = $this->get('app.manager.configuration');

        $message = \Swift_Message::newInstance()
            ->setSubject('Website message from '.$message->getName())
            ->setFrom(['website@silverpapillon.com' => 'Silver Papillon Website'])
            ->setTo([$config->value('contact.email', 'rmf@src.run') => 'Silver Papillon'])
            ->setReplyTo($message->getEmail())
            ->setBody(
                $this->renderView(
                    'email/message.html.twig',
                    [
                        'name'    => $message->getName(),
                        'message' => $message->getMessage(),
                        'email'   => $message->getEmail(),
                    ]
                ),
                'text/html'
            );

        $this->get('mailer')->send($message);
    }
}
