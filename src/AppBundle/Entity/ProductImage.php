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

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class ProductImage.
 *
 * @Vich\Uploadable
 */
class ProductImage
{
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
     * @var int
     */
    protected $weight;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var Product
     */
    protected $product;

    /**
     * @var File|null
     *
     * @Vich\UploadableField(mapping="image_products", fileNameProperty="image")
     */
    protected $imageFile;

    /**
     * Assign entity property default values.
     */
    public function __construct()
    {
        $this->createdOn = new \DateTime();
        $this->updatedOn = new \DateTime();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->image;
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
     * @param int $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return int
     o     */
    public function getWeight()
    {
        return $this->weight;
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
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     *
     * @return ProductImage
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }
}

/* EOF */
