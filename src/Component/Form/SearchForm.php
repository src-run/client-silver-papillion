<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Form;

use AppBundle\Form\SearchType;
use AppBundle\Model\Search;
use SR\Exception\Runtime\RuntimeException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class SearchForm
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var Search
     */
    private $model;

    /**
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface      $router
     */
    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function handle(Request $request): ?RedirectResponse
    {
        $this->form = $this->formFactory->create(SearchType::class, $this->model = new Search());
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            return new RedirectResponse($this->router->generate('app_search', [
                'search' => urlencode($this->model->getSearch()),
            ]), 302);
        }

        return null;
    }

    /**
     * @return FormView
     */
    public function createFormView(): FormView
    {
        if (!$this->form) {
            throw new RuntimeException('Cannot create view before form has been created.');
        }

        return $this->form->createView();
    }

    /**
     * @return null|FormInterface
     */
    public function getForm(): ?FormInterface
    {
        return $this->form;
    }

    /**
     * @return null|Search
     */
    public function getModel(): ?Search
    {
        return $this->model;
    }
}
