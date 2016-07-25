<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminController.
 */
class AdminController extends EasyAdminController
{
    /**
     * @Route("/", name="easyadmin")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }

    /**
     * @param object $entity
     */
    protected function prePersistEntity($entity)
    {
        if (method_exists($entity, 'setCreatedOn')) {
            $entity->setCreatedOn(new \DateTime());
        }
    }

    /**
     * @param object $entity
     */
    protected function preUpdateEntity($entity)
    {
        if (method_exists($entity, 'setUpdatedOn')) {
            $entity->setUpdatedOn(new \DateTime());
        }
    }

    /**
     * @param object $entity
     */
    protected function updateSlug($entity)
    {
        if (method_exists($entity, 'setSlug')) {
            $entity->setSlug($this->get('app.slugger')->slugifyEntity($entity, ['name']));
        }
    }
}

/* EOF */
