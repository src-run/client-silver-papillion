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
use AppBundle\Entity\Product;
use AppBundle\Repository\CategoryRepository;

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
        return $this->getRepository()->findAllEnabled();
    }

    /**
     * @param string ...$categorySlugs
     *
     * @return Category
     */
    public function getRandomNot(string ...$categorySlugs)
    {
        $categories = array_filter($this->getAllByWeight(), function (Category $category) use ($categorySlugs): bool {
            return !in_array($category->getSlug(), $categorySlugs);
        });

        shuffle($categories);

        return $categories[0];
    }

    /**
     * @return Category[]
     */
    public function getAllByWeight()
    {
        return array_filter($this->getRepository()->findAllOrderByWeight(), function (Category $category): bool {
            return 0 < count(array_filter($category->getProducts()->toArray(), function (Product $product): bool {
                return $product->isEnabled();
            }));
        });
    }
}
