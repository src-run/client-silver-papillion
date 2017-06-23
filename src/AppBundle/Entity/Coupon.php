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

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Coupon
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var bool/**
     * @Assert\Type("boolean")
     */
    private $enabled;

    /**
     * @var bool|null
     * @Assert\Type("boolean")
     */
    private $featured;

    /**
     * @var bool|null
     * @Assert\Type("boolean")
     */
    private $published;

    /**
     * @var \DateTime|null
     */
    private $expiration;

    /**
     * @var string
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var float|null
     */
    private $discountDollars;

    /**
     * @var float|null
     */
    private $discountPercent;

    /**
     * @var float|null
     */
    private $maximumValue;

    /**
     * @var float|null
     */
    private $minimumRequirement;

    public function __construct()
    {
        $this->enabled = true;
        $this->featured = false;
        $this->published = false;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled === true;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return bool
     */
    public function isFeatured(): bool
    {
        return $this->featured === true;
    }

    /**
     * @param bool|null $featured
     */
    public function setFeatured(bool $featured = null)
    {
        $this->featured = $featured;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->published === true;
    }

    /**
     * @param bool|null $published
     */
    public function setPublished(bool $published = null)
    {
        $this->published = $published;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpiration(): ?\DateTime
    {
        return $this->expiration;
    }

    /**
     * @param \DateTime|null $expiration
     */
    public function setExpiration(\DateTime $expiration = null)
    {
        $this->expiration = $expiration;
    }

    /**
     * @return bool
     */
    public function hasExpiration(): bool
    {
        return $this->expiration instanceof \DateTime;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = strtoupper($code);
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(string $description = null)
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function hasDescription(): bool
    {
        return $this->description !== null;
    }

    /**
     * @return float|null
     */
    public function getDiscountDollars(): ?float
    {
        return $this->discountDollars;
    }

    /**
     * @param float|null $discountDollars
     */
    public function setDiscountDollars(float $discountDollars = null)
    {
        $this->discountDollars = $discountDollars;
    }

    /**
     * @return bool
     */
    public function hasDiscountDollars(): bool
    {
        return $this->discountDollars !== null;
    }

    /**
     * @return float|null
     */
    public function getDiscountPercent(): ?float
    {
        return $this->discountPercent;
    }

    /**
     * @param float|null $discountPercent
     */
    public function setDiscountPercent(float $discountPercent = null)
    {
        $this->discountPercent = $discountPercent;
    }

    /**
     * @return bool
     */
    public function hasDiscountPercent(): bool
    {
        return $this->discountPercent !== null;
    }

    /**
     * @return float|null
     */
    public function getMaximumValue(): ?float
    {
        return $this->maximumValue;
    }

    /**
     * @param float|null $maximumValue
     */
    public function setMaximumValue(float $maximumValue = null)
    {
        $this->maximumValue = $maximumValue;
    }

    /**
     * @return bool
     */
    public function hasMaximumValue(): bool
    {
        return $this->maximumValue !== null;
    }

    /**
     * @return float|null
     */
    public function getMinimumRequirement(): ?float
    {
        return $this->minimumRequirement;
    }

    /**
     * @param float|null $minimumRequirement
     */
    public function setMinimumRequirement(float $minimumRequirement = null)
    {
        $this->minimumRequirement = $minimumRequirement;
    }

    /**
     * @return bool
     */
    public function hasMinimumRequirement(): bool
    {
        return $this->minimumRequirement !== null;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->discountPercent && $this->discountDollars) {
            $context->buildViolation('You cannot set a percentage discount when a dollar value is set!')
                ->atPath('discountPercent')
                ->addViolation();
            $context->buildViolation('You cannot set a dollar value discount when a percentage is set!')
                ->atPath('discountDollars')
                ->addViolation();
        }

        if ($this->expiration && $this->expiration->format('U') <= time()) {
            $context->buildViolation('You cannot set an expiration date in the past!')
                ->atPath('expiration')
                ->addViolation();
        }

        if ($this->minimumRequirement < 0) {
            $context->buildViolation('You cannot enter a negative value!')
                ->atPath('minimumRequirement')
                ->addViolation();
        }

        if ($this->maximumValue < 0) {
            $context->buildViolation('You cannot enter a negative value!')
                ->atPath('maximumValue')
                ->addViolation();
        }

        if (($this->discountPercent && false !== strpos($this->name, (string) $this->discountPercent)) ||
            ($this->discountDollars && false !== strpos($this->name, (string) $this->discountDollars))) {
            $context->buildViolation('You must not enter the discount value amount in the name!')
                ->atPath('name')
                ->addViolation();
        }

        if (0 === preg_match('{^[0-9a-z_-]+$}i', $this->code)) {
            $context->buildViolation('You must use only alphanumeric characters, dashes, and underscores!')
                ->atPath('code')
                ->addViolation();
        }
    }
}

/* EOF */
