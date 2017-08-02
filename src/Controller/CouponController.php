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
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class CouponController extends AbstractController
{
    /**
     * @var CouponManager
     */
    private $couponManager;

    /**
     * @param TwigEngine           $twig
     * @param RouterInterface      $router
     * @param SessionInterface     $session
     * @param FormFactory          $formFactory
     * @param ConfigurationManager $configuration
     * @param CouponManager        $couponManager
     */
    public function __construct(TwigEngine $twig, RouterInterface $router, SessionInterface $session, FormFactory $formFactory, ConfigurationManager $configuration, CouponManager $couponManager)
    {
        parent::__construct($twig, $router, $session, $formFactory, $configuration);

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
            '_c'      => static::class,
            'coupons' => $this->couponManager->getPublished()
        ]);
    }
}
