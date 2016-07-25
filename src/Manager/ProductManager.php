<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Repository\ProductRepository;

/**
 * Class ProductManager.
 */
class ProductManager extends AbstractManager
{
    /**
     * @var string
     */
    const ENTITY = Product::class;

    /**
     * @return ProductRepository|\Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository(static::ENTITY);
    }

    /**
     * @return Product[]
     */
    public function getFeatured()
    {
        return $this->getRepository()->findFeatured();
    }

    public function getAllFromCategory(Category $category)
    {
        return $this->getRepository()->findInCategory($category);
    }
}

/* EOF */
