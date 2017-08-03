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

use AppBundle\Manager\CategoryManager;
use AppBundle\Manager\ConfigurationManager;
use AppBundle\Manager\ProductManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

abstract class AbstractProductAwareController extends AbstractController
{
    /**
     * @var CategoryManager
     */
    protected $categoryManager;

    /**
     * @var ProductManager
     */
    protected $productManager;

    /**
     * @param EngineInterface      $engine
     * @param RouterInterface      $router
     * @param SessionInterface     $session
     * @param FormFactoryInterface $formFactory
     * @param ConfigurationManager $configuration
     * @param CategoryManager      $categoryManager
     * @param ProductManager       $productManager
     */
    public function __construct(EngineInterface $engine, RouterInterface $router, SessionInterface $session, FormFactoryInterface $formFactory, ConfigurationManager $configuration, CategoryManager $categoryManager, ProductManager $productManager)
    {
        parent::__construct($engine, $router, $session, $formFactory, $configuration);

        $this->categoryManager = $categoryManager;
        $this->productManager = $productManager;
    }
}
