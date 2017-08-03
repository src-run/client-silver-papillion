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
use AppBundle\Manager\HoursManager;
use AppBundle\Util\Directions;
use AppBundle\Util\MapperStatic;
use AppBundle\Util\MapperStreet;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class LocationController extends AbstractController
{
    /**
     * @var HoursManager
     */
    private $hoursManager;

    /**
     * @var MapperStatic
     */
    private $mapperStatic;

    /**
     * @var MapperStreet
     */
    private $mapperStreet;

    /**
     * @var Directions
     */
    private $directions;

    /**
     * @param EngineInterface      $engine
     * @param RouterInterface      $router
     * @param SessionInterface     $session
     * @param FormFactoryInterface $formFactory
     * @param ConfigurationManager $configuration
     * @param HoursManager         $hoursManager
     * @param MapperStatic         $mapperStatic
     * @param MapperStreet         $mapperStreet
     */
    public function __construct(EngineInterface $engine, RouterInterface $router, SessionInterface $session, FormFactoryInterface $formFactory, ConfigurationManager $configuration, HoursManager $hoursManager, MapperStatic $mapperStatic, MapperStreet $mapperStreet, Directions $directions)
    {
        parent::__construct($engine, $router, $session, $formFactory, $configuration);

        $this->hoursManager = $hoursManager;
        $this->mapperStatic = $mapperStatic;
        $this->mapperStreet = $mapperStreet;
        $this->directions = $directions;
    }

    /**
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render('AppBundle:location:index.html.twig', [
            'hours'         => $this->hoursManager->getAll(),
            'staticMaps'    => $this->mapperStatic->generate(),
            'streetView'    => $this->mapperStreet->generate(),
            'directionsUri' => $this->directions->generate(),
        ]);
    }
}
