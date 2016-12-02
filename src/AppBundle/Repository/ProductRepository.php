<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Paginator;

/**
 * Class ProductRepository.
 */
class ProductRepository extends AbstractRepository
{
    /**
     * @return Product[]
     */
    public function findFeatured()
    {
        return $this->getResult(function (QueryBuilder $b) {
            $b
                ->where('p.featured = 1')
                ->andWhere('p.enabled = 1')
                ->orderBy('p.name');
        });
    }

    /**
     * @param Category $category
     *
     * @return Product[]
     */
    public function findInCategory(Category $category)
    {
        return $this->getResult(function (QueryBuilder $b) use ($category) {
            $b
                ->where('p.category = :category')
                ->andWhere('p.enabled = 1')
                ->setParameter('category', $category)
                ->orderBy('p.name');
        });
    }

    /**
     * @param Category $category
     * @param Product[] ...$exclusions
     *
     * @return Product[]
     */
    public function findInCategoryWithExclusions(Category $category, Product ...$exclusions)
    {
        return $this->getResult(function (QueryBuilder $b) use ($category, $exclusions) {
            $b
                ->where('p.category = :category')
                ->andWhere('p.enabled = 1');

            foreach ($exclusions as $i => $product) {
                $b
                    ->andWhere('p != :product_'.$i)
                    ->setParameter('product_'.$i, $product);
            }

            $b
                ->setParameter('category', $category)
                ->orderBy('p.name');
        });
    }

    /**
     * @param Category  $category
     * @param Paginator $paginator
     * @param int       $page
     * @param int       $limit
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function findInCategoryPaginated(Category $category, Paginator $paginator, $page, $limit = 12)
    {
        $query = $this->getQuery(function (QueryBuilder $b) use ($category) {
            $b
                ->where('p.category = :category')
                ->andWhere('p.enabled = 1')
                ->setParameter('category', $category)
                ->orderBy('p.name');
        });

        return $paginator->paginate($query, $page, $limit);
    }
}

/* EOF */
