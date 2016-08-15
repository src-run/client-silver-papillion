<?php

namespace AppBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * CarouselEntry
 *
 * @Vich\Uploadable
 */
class CarouselSlide
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $weight;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var string
     */
    protected $caption;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var File|null
     *
     * @Vich\UploadableField(mapping="carousel_images", fileNameProperty="image")
     */
    protected $imageFile;

    /**
     * Assign entity property default values.
     */
    public function __construct()
    {
        $this->enabled = true;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * Set caption
     *
     * @param string $caption
     *
     * @return CarouselSlide
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @return bool
     */
    public function hasCaption()
    {
        return $this->caption !== null && !empty($this->caption);
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
}

