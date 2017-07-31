<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

use AppBundle\Manager\CategoryManager;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

/**
 * Class CategoryManagerExtension.
 */
class CategoryManagerExtension extends TwigExtension
{
    /**
     * @var CategoryManager
     */
    private $manager;

    public function __construct()
    {
        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('get_categories', [$this, 'getCategories'])
        ]);
    }

    /**
     * @param CategoryManager $manager
     */
    public function setCategoryManager(CategoryManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return \AppBundle\Entity\Category[]
     */
    public function getCategories()
    {
        return $this->manager->getAll();
    }
}

/* EOF */
