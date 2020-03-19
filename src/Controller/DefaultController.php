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

use AppBundle\Entity\Product;
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
        $featuredWineCaddies = $this->productManager->getFeaturedWineCaddyProducts(
            $this->configurationValue('product.count.featured_wine_caddies', 3)
        );
        $featuredWineCaddiesIds = array_map(function (Product $product): int {
            return $product->getId();
        }, $featuredWineCaddies);

        $featuredCategory = $this->categoryManager->getRandomNot('wine-caddies');
        $featuredCategoryProducts = $this->productManager->getRandomProductsInCategory(
            $featuredCategory,
            $featuredWineCaddiesIds,
            $this->configurationValue('product.count.featured_category', 3)
        );
        $featuredCategoryIds = array_map(function (Product $product): int {
            return $product->getId();
        }, $featuredCategoryProducts);

        $featuredRandom = $this->productManager->getRandomFromAllCategories(
            array_merge($featuredWineCaddiesIds, $featuredCategoryIds),
            $this->configurationValue('product.count.featured', 3)
        );

        return $this->render('AppBundle:default:index.html.twig', [
            'featured_wine_caddy_products' => $featuredWineCaddies,
            'featured_category' => $featuredCategory,
            'featured_category_products' => $featuredCategoryProducts,
            'featured_random_products' => $featuredRandom,
            'hours' => $this->hoursManager->getAll(),
            'maps_static' => $this->mapperStatic->generate(
                $this->configurationValue('maps.size.default', '420x220')
            ),
            'coupon_modal' => !$this->couponManager->getCouponViewedState(),
        ]);
    }

    public function employeeScheduleAction(): Response
    {
        return $this->redirectTemporary("https://calendar.google.com/calendar/embed?src=silverpapillon.com_uh806jv57jr8cmoh33f7895sg0%40group.calendar.google.com&ctz=America%2FNew_York");
    }
}
