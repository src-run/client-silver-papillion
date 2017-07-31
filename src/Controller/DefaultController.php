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

use AppBundle\Manager\CategoryManager;
use AppBundle\Manager\ConfigurationManager;
use AppBundle\Manager\HoursManager;
use AppBundle\Manager\ProductManager;
use AppBundle\Util\MapperStatic;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class DefaultController extends AbstractProductAwareController
{
    /**
     * @var HoursManager
     */
    private $hoursManager;

    /**
     * @var MapperStatic
     */
    private $mapper;

    /**
     * @param TwigEngine           $twig
     * @param RouterInterface      $router
     * @param SessionInterface     $session
     * @param FormFactory          $formFactory
     * @param ConfigurationManager $configuration
     * @param CategoryManager      $categoryManager
     * @param ProductManager       $productManager
     * @param HoursManager         $hoursManager
     * @param MapperStatic         $mapper
     */
    public function __construct(TwigEngine $twig, RouterInterface $router, SessionInterface $session, FormFactory $formFactory, ConfigurationManager $configuration, CategoryManager $categoryManager, ProductManager $productManager, HoursManager $hoursManager, MapperStatic $mapper)
    {
        parent::__construct($twig, $router, $session, $formFactory, $configuration, $categoryManager, $productManager);

        $this->hoursManager = $hoursManager;
        $this->mapper = $mapper;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $count = $this->configurationValue('product.count.featured', 3);

        return $this->render('AppBundle:default:index.html.twig', [
            '_c'         => static::class,
            'hours'      => $this->hoursManager->getAll(),
            'featured'   => $this->productManager->getFeatured($count),
            'staticMaps' => $this->mapper->generate('420x220'),
            'showCoupon' => $this->showCouponState(),
        ]);
    }

    /**
     * @return bool
     */
    private function showCouponState(): bool
    {
        $timeNow = time();

        if (($this->sessionGet('coupon_featured') ?? $timeNow) <= $timeNow) {
            $this->sessionSet('coupon_featured', strtotime('+10 minute'));

            return true;
        }

        return false;
    }
}

/* EOF */
