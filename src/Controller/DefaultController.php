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
use AppBundle\Manager\CouponManager;
use AppBundle\Manager\HoursManager;
use AppBundle\Manager\ProductManager;
use AppBundle\Util\MapperStatic;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class DefaultController extends AbstractProductAwareController
{
    /**
     * @var CouponManager
     */
    private $couponManager;

    /**
     * @var HoursManager
     */
    private $hoursManager;

    /**
     * @var MapperStatic
     */
    private $mapperStatic;

    /**
     * @param EngineInterface      $engine
     * @param RouterInterface      $router
     * @param SessionInterface     $session
     * @param FormFactoryInterface $formFactory
     * @param ConfigurationManager $configuration
     * @param CategoryManager      $categoryManager
     * @param ProductManager       $productManager
     * @param CouponManager        $couponManager
     * @param HoursManager         $hoursManager
     * @param MapperStatic         $mapperStatic
     */
    public function __construct(EngineInterface $engine, RouterInterface $router, SessionInterface $session, FormFactoryInterface $formFactory, ConfigurationManager $configuration, CategoryManager $categoryManager, ProductManager $productManager, CouponManager $couponManager, HoursManager $hoursManager, MapperStatic $mapperStatic)
    {
        parent::__construct($engine, $router, $session, $formFactory, $configuration, $categoryManager, $productManager);

        $this->couponManager = $couponManager;
        $this->hoursManager = $hoursManager;
        $this->mapperStatic = $mapperStatic;
    }

    /**
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render('AppBundle:default:index.html.twig', [
            'featured'   => $this->productManager->getFeatured(
                $this->configurationValue('product.count.featured', 3)
            ),
            'hours'      => $this->hoursManager->getAll(),
            'staticMaps' => $this->mapperStatic->generate(
                $this->configurationValue('maps.size.default', '420x220')
            ),
            'showCoupon' => !$this->couponManager->getCouponViewedState(),
        ]);
    }
}
