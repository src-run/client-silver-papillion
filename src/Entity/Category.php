<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Category.
 *
 * @Vich\Uploadable
 */
class Category
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var int
     */
    protected $weight;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var Product[]|ArrayCollection
     **/
    protected $products;

    /**
     * Setup default property values.
     */
    public function __construct()
    {
        $this->enabled = true;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $weight
     *
     * @return Category
     */
    public function setWeight($weight = null)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return bool
     */
    public function hasWeight()
    {
        return $this->weight !== null;
    }

    /**
     * @param string|null $description
     *
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function hasDescription()
    {
        return $this->description !== null;
    }

    /**
     * @param bool $enabled
     *
     * @return Category
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled === true;
    }

    /**
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = (string) $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $slug
     *
     * @return Category
     */
    public function setSlug($slug = null)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return Product[]|PersistentCollection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return Product[]|ArrayCollection
     */
    public function getProductsShuffled()
    {
        $products = array_filter($this->products->toArray(), function (Product $product): bool {
            return $product->isEnabled();
        });

        shuffle($products);

        return new ArrayCollection($products);
    }

    /**
     * @param ArrayCollection|Product[] $products
     *
     * @return Category
     */
    public function setProducts(ArrayCollection $products)
    {
        $this->products = $products->filter(function ($product) {
            return $product instanceof Product;
        });

        return $this;
    }

    /**
     * @param Product $product
     *
     * @return Category
     */
    public function addProduct(Product $product)
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    /**
     * @param Product $product
     *
     * @return Category
     */
    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);

        return $this;
    }
}
