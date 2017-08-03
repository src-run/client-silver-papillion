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

use AppBundle\Component\Environment\Environment;
use AppBundle\Component\Mailer\Mailer;
use AppBundle\Form\MessageType;
use AppBundle\Manager\ConfigurationManager;
use AppBundle\Model\Message;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class ContactController extends AbstractController
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $environment;

    /**
     * @param EngineInterface      $engine
     * @param RouterInterface      $router
     * @param SessionInterface     $session
     * @param FormFactoryInterface $formFactory
     * @param ConfigurationManager $configuration
     * @param Mailer               $mailer
     */
    public function __construct(EngineInterface $engine, RouterInterface $router, SessionInterface $session, FormFactoryInterface $formFactory, ConfigurationManager $configuration, Mailer $mailer, Environment $environment)
    {
        parent::__construct($engine, $router, $session, $formFactory, $configuration);

        $this->mailer = $mailer;
        $this->environment = $environment;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $form = $this->createForm(MessageType::class, $message = new Message());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->sendMessage($message);

            return $this->render('AppBundle:contact:sent.html.twig');
        }

        return $this->render('AppBundle:contact:index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Message $message
     */
    protected function sendMessage(Message $message): void
    {
        $body = $this->renderView('email/message.html.twig', [
            'name'    => $message->getName(),
            'message' => $message->getMessage(),
            'email'   => $message->getEmail(),
        ]);

        $unit = $this->mailer->createMessage(sprintf('Website message from "%s"', $message->getName()));
        $unit->setFrom(['website@silverpapillon.com' => 'Silver Papillon Website']);
        $unit->setReplyTo($message->getEmail());
        $unit->setBody($body, 'text/html');

        if ($this->environment->isDevelopment()) {
            $unit->setTo('sp-contact-dev@src.run');
        } else {
            $unit->setTo([$this->configurationValue('contact.email', 'sp-contact-dev@src.run') => 'Silver Papillon']);
        }

        $this->mailer->queue($unit);
    }
}
