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
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Product.
 *
 * @Vich\Uploadable
 */
class Product
{
    /**
     * @var float
     */
    const RATE_SHIPPING_DEFAULT = 13.45;

    /**
     * @var float
     */
    const RATE_TAX_PERCENTAGE = 0.0635;

    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * @var \DateTime
     */
    protected $updatedOn;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var bool
     */
    protected $featured;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var ProductImage[]|ArrayCollection
     */
    protected $images;

    /**
     * @var File|null
     *
     * @Vich\UploadableField(mapping="image_products", fileNameProperty="image")
     */
    protected $imageFile;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var float
     */
    protected $shipping;

    /**
     * @var bool
     */
    protected $taxable;

    /**
     * @var Category
     */
    protected $category;

    /**
     * @var Product[]|ArrayCollection
     */
    protected $relatedProducts;

    /**
     * @var Product[]|ArrayCollection
     */
    protected $inverseRelatedProducts;

    /**
     * Assign entity property default values.
     */
    public function __construct()
    {
        $this->createdOn = new \DateTime();
        $this->updatedOn = new \DateTime();
        $this->enabled = true;
        $this->featured = false;
        $this->taxable = true;
        $this->relatedProducts = new ArrayCollection();
        $this->inverseRelatedProducts = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param \DateTime $createdOn
     *
     * @return $this
     */
    public function setCreatedOn(\DateTime $createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @param \DateTime $updatedOn
     *
     * @return $this
     */
    public function setUpdatedOn(\DateTime $updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
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
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return bool
     */
    public function hasSku()
    {
        return $this->sku !== null;
    }

    /**
     * @param bool $enabled
     *
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (bool) $enabled;

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
     * @param string|null $description
     *
     * @return $this
     */
    public function setDescription($description = null)
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
     * @return $this
     */
    public function clearDescription()
    {
        $this->description = null;

        return $this;
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
     * @return ProductImage[]|ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @return bool
     */
    public function hasImages()
    {
        return !$this->images->isEmpty();
    }

    /**
     * @param ProductImage $image
     *
     * @return $this
     */
    public function addImage(ProductImage $image)
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProduct($this);
        }

        return $this;
    }

    /**
     * @param ProductImage $image
     *
     * @return $this
     */
    public function removeImage(ProductImage $image)
    {
        dump($image);
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            $image->setProduct(null);
        }

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = (float) $price;

        return $this;
    }

    /**
     * @return float
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @param float $shipping
     *
     * @return $this
     */
    public function setShipping($shipping)
    {
        if (empty($shipping) && $shipping !== 0.0) {
            $this->shipping = null;
        } else {
            $this->shipping = (float) $shipping;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasShipping()
    {
        return $this->shipping !== null;
    }

    /**
     * @return bool
     */
    public function hasNoShipping()
    {
        return true;
        return $this->shipping === 0.0;
    }

    /**
     * @param float $default
     *
     * @return float
     */
    public function getShippingRate($default = self::RATE_SHIPPING_DEFAULT)
    {
        return 0.0;
        return $this->hasShipping() ? $this->getShipping() : $default;
    }

    /**
     * @param bool $taxable
     *
     * @return $this
     */
    public function setTaxable($taxable)
    {
        $this->taxable = (bool) $taxable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTaxable()
    {
        return $this->taxable;
    }

    /**
     * @param float $default
     *
     * @return float
     */
    public function getTaxableRate($default = self::RATE_TAX_PERCENTAGE)
    {
        return $this->isTaxable() ? $default : 0.0;
    }

    /**
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param \AppBundle\Entity\Category $category
     *
     * @return $this
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Product[]|ArrayCollection
     */
    public function getRelatedProducts()
    {
        return $this->relatedProducts;
    }

    /**
     * @return Product[]|ArrayCollection
     */
    public function getRelatedProductsExcludingSelf()
    {
        $relatedProduct = $this->relatedProducts;

        if ($relatedProduct->contains($this)) {
            $relatedProduct->removeElement($this);
        };

        return $relatedProduct;
    }

    /**
     * @param null|Product[]|ArrayCollection $relatedProducts
     *
     * @return $this
     */
    public function setRelatedProducts($relatedProducts = null)
    {
        $this->relatedProducts = $relatedProducts;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasRelatedProducts()
    {
        return !$this->relatedProducts->isEmpty();
    }

    /**
     * @return Product[]|ArrayCollection
     */
    public function getInverseRelatedProducts()
    {
        return $this->inverseRelatedProducts;
    }

    /**
     * @return bool
     */
    public function hadInverseRelatedProducts(): bool
    {
        return !$this->inverseRelatedProducts->isEmpty();
    }
}

/* EOF */
