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
use AppBundle\Model\SearchProduct;
use AppBundle\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Paginator;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\VarDumper\VarDumper;

class ProductManager extends AbstractManager
{
    /**
     * @var string
     */
    const ENTITY = Product::class;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * @param EntityManagerInterface $em
     * @param Paginator              $paginator
     */
    public function __construct(EntityManagerInterface $em, Paginator $paginator, CacheItemPoolInterface $cache)
    {
        parent::__construct($em);

        $this->paginator = $paginator;
        $this->cache = $cache;
    }

    /**
     * @return ProductRepository|\Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository(static::ENTITY);
    }

    /**
     * @param int $limit
     *
     * @return Product[]
     */
    public function getFeaturedWineCaddyProducts($limit = 3)
    {
        $featured = $this->getRepository()->findFeaturedInCategory(
            $this->em->getRepository(Category::class)->findBy([
                'slug' => 'wine-caddies',
            ])[0]
        );
        shuffle($featured);

        return array_slice($featured, 0, $limit);
    }

    /**
     * @param Category $category
     * @param array    $notIds
     * @param int      $limit
     *
     * @return Product[]
     */
    public function getRandomProductsInCategory(Category $category, array $notIds, int $limit = 3)
    {
        $random = array_filter($this->getRepository()->findInCategory($category), function (Product $product) use ($notIds): bool {
            return !in_array($product->getId(), $notIds);
        });

        shuffle($random);

        return array_slice($random, 0, $limit);
    }

    /**
     * @param array $notIds
     * @param int   $limit
     *
     * @return Product[]
     */
    public function getRandomFromAllCategories(array $notIds, int $limit = 3)
    {
        $categories = $this->em->getRepository(Category::class)->findAllOrderByWeight();
        $random = [];
        foreach ($categories as $c) {
            $r = array_filter($this->getRepository()->findInCategory($c), function (Product $product) use ($notIds): bool {
                return !in_array($product->getId(), $notIds);
            });
            $c = count($r);
            shuffle($r);
            if ($c > 0) {
                $random[] = array_shift($r);
            }
            if ($c > 1) {
                $random[] = array_shift($r);
            }
            if ($c > 2) {
                $random[] = array_shift($r);
            }
        }

        shuffle($random);

        return array_slice($random, 0, $limit);
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
     * @return SearchProduct[]
     */
    public function getIdAndTitles()
    {
        return array_map(function (array $result) {
            return new SearchProduct($result['id'], $result['name']);
        }, $this->getRepository()->findIdAndTitlesArray());
    }

    /**
     * @param int $id
     *
     * @return Product
     */
    public function getMatchingId(int $id): Product
    {
        return $this->getRepository()->findMatchingId($id);
    }

    /**
     * @param string $search
     *
     * @return Product[]
     */
    public function getByNameKeywords(string $search): array
    {
        return $this->searchByNameSearch(str_word_count($search, 1));
    }

    /**
     * @param string[] $searches
     *
     * @return Product[]
     */
    public function searchByNameSearch(array $searches): array
    {
        $item = $this->cache->getItem($this->getCacheKey(__FUNCTION__, implode('_', $searches)));

        if (!$item->isHit()) {
            $item->set(array_map(function (SearchProduct $product) {
                return $this->getMatchingId($product->getId());
            }, array_filter($this->getIdAndTitles(), function (SearchProduct $product) use ($searches) {
                foreach ($searches as $search) {
                    if (!$product->isNameMatch($search)) {
                        return false;
                    }
                }

                return true;
            })));
            $this->cache->save($item);
        }

        return $item->get();
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

        $related = array_filter($related, function (Product $product) {
            return $product->isEnabled();
        });

        $related = array_filter($related, function (Product $p) use ($product) {
            return $p->getId() !== $product->getId();
        });

        return array_unique($related);
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

    /**
     * @param string $sku
     *
     * @return Product
     */
    public function getBySku(string $sku): Product
    {
        return $this->getRepository()->findSingleBySku($sku);
    }

    /**
     * @param string $type
     * @param string $context
     *
     * @return string
     */
    private function getCacheKey(string $type, string $context = 'main', ...$more): string
    {
        return vsprintf('__%s__%s__%s__%s', [
            preg_replace('{[^a-z0-9]+}i', '-', __CLASS__),
            md5($type),
            md5($context),
            md5(implode('-', array_map(function ($value) {
                return var_export($value, true);
            }, $more)))
        ]);
    }
}
