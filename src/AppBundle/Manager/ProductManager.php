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
use AppBundle\Repository\ProductRepository;
use Knp\Component\Pager\Paginator;
use Symfony\Component\VarDumper\VarDumper;

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
     * @var Paginator
     */
    protected $paginator;

    /**
     * @param Paginator $paginator
     */
    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

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
    public function getFeatured($limit = 3)
    {
        $featured = $this->getRepository()->findFeatured();
        shuffle($featured);

        return array_slice($featured, 0, $limit);
    }

    /**
     * @param Category $category
     *
     * @return \AppBundle\Entity\Product[]
     */
    public function getAllFromCategory(Category $category)
    {
        return $this->getRepository()->findInCategory($category);
    }

    /**
     * @param Category $category
     * @param int      $limit
     *
     * @return \AppBundle\Entity\Product[]
     */
    public function getRandomFromCategory(Category $category, $limit)
    {
        $products = $this->getRepository()->findInCategory($category);
        shuffle($products);

        return array_splice($products, 0, $limit);
    }

    /**
     * @param Product $product
     *
     * @return Product[]
     */
    public function getRelated(Product $product): array
    {
        $related = [];

        foreach ($product->getRelatedProductsExcludingSelf() as $r) {
            $related = array_merge($related, [$r], $r->getRelatedProductsExcludingSelf()->toArray());
        }

        foreach ($product->getInverseRelatedProducts() as $r) {
            $related = array_merge($related, [$r], $r->getRelatedProductsExcludingSelf()->toArray());
        }

        return array_unique(array_filter(array_filter($related, function (Product $product) {
            return $product->isEnabled();
        }), function (Product $p) use ($product) {
            return $p->getId() !== $product->getId();
        }));
    }

    /**
     * @param Product $product
     * @param int     $limit
     *
     * @return \AppBundle\Entity\Product[]
     */
    public function getRandomSimilar(Product $product, $limit)
    {
        $products = $this->getRepository()->findInCategoryWithExclusions(
            $product->getCategory(),
            ...array_merge([$product], $product->getRelatedProducts()->toArray())
        );
        shuffle($products);

        return array_splice($products, 0, $limit);
    }

    /**
     * @param Product $product
     * @param int     $limit
     *
     * @return \AppBundle\Entity\Product[]
     */
    public function getRandomOther(Product $product, $limit)
    {
        $products = $this->getRepository()->findNotInCategory($product->getCategory());
        shuffle($products);

        return array_splice($products, 0, $limit);
    }

    /**
     * @param Category $category
     * @param int      $page
     * @param int      $limit
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface|Product[]
     */
    public function getAllFromCategoryPaginated(Category $category, $page, $limit = 12)
    {
        return $this->getRepository()->findInCategoryPaginated($category, $this->paginator, $page, $limit);
    }
}

/* EOF */
