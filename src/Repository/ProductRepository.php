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
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Paginator;

/**
 * Class ProductRepository.
 */
class ProductRepository extends AbstractRepository
{
    /**
     * @return array[]
     */
    public function findIdAndTitlesArray()
    {
        $query = $this->createQueryBuilder('p')
            ->select('p.id, p.name')
            ->orderBy('p.name')
            ->getQuery();

        if (self::CACHE_ENABLED) {
            $index = $this->getCacheKey($query);
            $cache = $this->getCacheDriver();

            if ($cache->contains($index)) {
                return $cache->fetch($index);
            }
        }

        $result = $query
            ->setHydrationMode(Query::HYDRATE_ARRAY)
            ->getResult();

        if (isset($cache) && isset($index)) {
            $cache->save($index, $result, self::DEFAULT_TTL);
        }

        return $result;
    }

    /**
     * @param int $id
     *
     * @return Product
     */
    public function findMatchingId(int $id)
    {
        return $this->getResult(function (QueryBuilder $queryBuilder) use ($id) {
            $queryBuilder
                ->where('p.id = :id')
                ->setParameter('id', $id);
        }, true);
    }

    /**
     * @return Product[]
     */
    public function findFeatured()
    {
        return $this->getResult(function (QueryBuilder $queryBuilder) {
            $queryBuilder
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
        return $this->getResult(function (QueryBuilder $queryBuilder) use ($category) {
            $queryBuilder
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
        return $this->getResult(function (QueryBuilder $queryBuilder) use ($category, $exclusions) {
            $queryBuilder
                ->where('p.category = :category')
                ->andWhere('p.enabled = 1');

            foreach ($exclusions as $i => $product) {
                $queryBuilder
                    ->andWhere('p != :product_'.$i)
                    ->setParameter('product_'.$i, $product);
            }

            $queryBuilder
                ->setParameter('category', $category)
                ->orderBy('p.name');
        });
    }

    /**
     * @param Category $category
     *
     * @return Product[]
     */
    public function findNotInCategory(Category $category)
    {
        return $this->getResult(function (QueryBuilder $queryBuilder) use ($category) {
            $queryBuilder
                ->where('p.category != :category')
                ->andWhere('p.enabled = 1')
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
        $query = $this->getQuery(function (QueryBuilder $queryBuilder) use ($category) {
            $queryBuilder
                ->where('p.category = :category')
                ->andWhere('p.enabled = 1')
                ->setParameter('category', $category)
                ->orderBy('p.name');
        });

        return $paginator->paginate($query, $page, $limit);
    }
}

/* EOF */
