<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Category;
use AppBundle\Repository\CategoryRepository;

/**
 * Class CategoryManager.
 */
class CategoryManager extends AbstractManager
{
    /**
     * @var string
     */
    const ENTITY = Category::class;

    /**
     * @return CategoryRepository|\Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository(static::ENTITY);
    }

    /**
     * @return Category[]
     */
    public function getAll()
    {
        return $this->getRepository()->findAll();
    }
}

/* EOF */
