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

use AppBundle\Manager\ConfigurationManager;
use AppBundle\Manager\CouponManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class CouponController extends AbstractController
{
    /**
     * @var CouponManager
     */
    private $couponManager;

    /**
     * @param EngineInterface      $engine
     * @param RouterInterface      $router
     * @param SessionInterface     $session
     * @param FormFactoryInterface $formFactory
     * @param ConfigurationManager $configuration
     * @param CouponManager        $couponManager
     */
    public function __construct(EngineInterface $engine, RouterInterface $router, SessionInterface $session, FormFactoryInterface $formFactory, ConfigurationManager $configuration, CouponManager $couponManager)
    {
        parent::__construct($engine, $router, $session, $formFactory, $configuration);

        $this->couponManager = $couponManager;
    }

    /**
     * @ParamConverter("product")
     *
     * @return Response
     */
    public function listAction(): Response
    {
        return $this->render('AppBundle:coupon:list.html.twig', [
            'coupons' => $this->couponManager->getPublished()
        ]);
    }
}
