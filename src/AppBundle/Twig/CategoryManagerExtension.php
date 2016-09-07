<?php

/*
 * This file is part of the `src-run/srw-client-silverpapillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

use AppBundle\Manager\CategoryManager;

/**
 * Class CategoryManagerExtension.
 */
class CategoryManagerExtension extends \Twig_Extension
{
    /**
     * @var CategoryManager
     */
    private $manager;

    /**
     * @param CategoryManager $manager
     */
    public function setCategoryManager(CategoryManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return \Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('get_categories', [$this, 'getCategories']),
        ];
    }

    /**
     * @return \AppBundle\Entity\Category[]
     */
    public function getCategories()
    {
        return $this->manager->getAll();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'category_manager_extension';
    }
}

/* EOF */
