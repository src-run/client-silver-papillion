<?php

/*
 * This file is part of the `src-run/rf-app` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Manager\ConfigurationManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

abstract class AbstractController
{
    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ConfigurationManager
     */
    private $configuration;

    /**
     * @param EngineInterface      $engine
     * @param RouterInterface      $router
     * @param SessionInterface     $session
     * @param FormFactoryInterface $formFactory
     * @param ConfigurationManager $configuration
     */
    public function __construct(EngineInterface $engine, RouterInterface $router, SessionInterface $session, FormFactoryInterface $formFactory, ConfigurationManager $configuration)
    {
        $this->engine = $engine;
        $this->router = $router;
        $this->session = $session;
        $this->formFactory = $formFactory;
        $this->configuration = $configuration;
    }

    /**
     * @param string $route
     * @param array  $parameters
     *
     * @return string
     */
    protected function renderView(string $route, array $parameters = []): string
    {
        return $this->engine->render($route, $parameters);
    }

    /**
     * @param string        $route
     * @param array         $parameters
     * @param Response|null $response
     *
     * @return Response
     */
    protected function render(string $route, array $parameters = [], Response $response = null): Response
    {
        return $this->engine->renderResponse($route, $parameters, $response);
    }

    /**
     * @param string   $content
     * @param int|null $status
     * @param array    $headers
     *
     * @return Response
     */
    protected function response(string $content, int $status = null, array $headers = []): Response
    {
        return new Response($content, $status ?: 200, $headers);
    }

    /**
     * @param string   $url
     * @param int|null $status
     * @param array    $headers
     *
     * @return RedirectResponse
     */
    protected function redirect(string $url, int $status = null, array $headers = []): RedirectResponse
    {
        return new RedirectResponse($url, $status ?: 302, $headers);
    }

    /**
     * @param string $url
     * @param array  $headers
     *
     * @return RedirectResponse
     */
    protected function redirectTemporary(string $url, array $headers = []): RedirectResponse
    {
        return $this->redirect($url, 302, $headers);
    }

    /**
     * @param string $name
     * @param array  $parameters
     *
     * @return RedirectResponse
     */
    protected function redirectRouteTemporary(string $name, array $parameters = []): RedirectResponse
    {
        return $this->redirectTemporary($this->route($name, $parameters));
    }

    /**
     * @param string $url
     * @param array  $headers
     *
     * @return RedirectResponse
     */
    protected function redirectPermanent(string $url, array $headers = []): RedirectResponse
    {
        return $this->redirect($url, 301, $headers);
    }

    /**
     * @param string $name
     * @param array  $parameters
     *
     * @return RedirectResponse
     */
    protected function redirectRoutePermanent(string $name, array $parameters = []): RedirectResponse
    {
        return $this->redirectPermanent($this->route($name, $parameters));
    }

    /**
     * @param string  $name
     * @param mixed[] $parameters
     *
     * @return string
     */
    protected function route(string $name, array $parameters = []): string
    {
        return $this->router->generate($name, $parameters);
    }

    /**
     * @return RouterInterface
     */
    protected function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param string $type    The fully qualified class name of the form type
     * @param mixed  $data    The initial data for the form
     * @param array  $options Options for the form
     *
     * @return FormInterface
     */
    protected function createForm($type, $data = null, array $options = array())
    {
        return $this->formFactory->create($type, $data, $options);
    }

    /**
     * @return FormFactoryInterface
     */
    protected function getFormFactory(): FormFactoryInterface
    {
        return $this->formFactory;
    }

    /**
     * @param string     $index
     * @param mixed|null $default
     *
     * @return string
     */
    protected function configurationValue(string $index, $default = null)
    {
        return $this->configuration->value($index, $default);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    protected function sessionGet(string $name)
    {
        return $this->session->get($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function sessionHas(string $name): bool
    {
        return $this->session->has($name);
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return AbstractController
     */
    protected function sessionSet(string $name, $value): self
    {
        $this->session->set($name, $value);

        return $this;
    }

    /**
     * @return AbstractController
     */
    protected function sessionSave(): self
    {
        $this->session->save();

        return $this;
    }
}
