<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @var bool
     */
    protected $enabled;

    /**
     * @var bool
     */
    protected $featured;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var File|null
     *
     * @Vich\UploadableField(mapping="category_images", fileNameProperty="image")
     */
    protected $imageFile;

    /**
     * @var Product[]|ArrayCollection
     **/
    protected $products;

    /**
     * @var Category|null
     **/
    protected $parent;

    /**
     * @var Category[]|ArrayCollection
     */
    protected $children;

    /**
     * Setup default property values.
     */
    public function __construct()
    {
        $this->enabled = true;
        $this->featured = false;
        $this->products = new ArrayCollection();
        $this->children = new ArrayCollection();
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
     * @param bool $enabled
     *
     * @return $this
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
        return $this->enabled;
    }

    /**
     * @param bool $featured
     *
     * @return $this
     */
    public function setFeatured($featured)
    {
        $this->featured = (bool) $featured;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFeatured()
    {
        return $this->featured;
    }

    /**
     * @param string $name
     *
     * @return $this
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
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;

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
     * @param string $image
     *
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return bool
     */
    public function hasImage()
    {
        return $this->image !== null;
    }

    /**
     * @return $this
     */
    public function clearImage()
    {
        $this->image = null;

        return $this;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\File|null $image
     *
     * @return $this
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @return $this
     */
    public function clearImageFile()
    {
        $this->imageFile = null;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasImageFile()
    {
        return $this->imageFile !== null;
    }

    /**
     * @param \AppBundle\Entity\Category|null $parent
     *
     * @return $this
     */
    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return bool
     */
    public function hasParent()
    {
        return $this->parent !== null;
    }

    /**
     * @return \AppBundle\Entity\Category[]|\Doctrine\Common\Collections\ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection|Product[] $products
     *
     * @return $this
     */
    public function setProducts(ArrayCollection $products)
    {
        $this->products = $products->filter(function ($product) {
            return $product instanceof Product;
        });

        return $this;
    }

    /**
     * @param \AppBundle\Entity\Product $product
     *
     * @return $this
     */
    public function addProduct(Product $product)
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    /**
     * @param \AppBundle\Entity\Product $product
     *
     * @return $this
     */
    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);

        return $this;
    }
}

/* EOF */
