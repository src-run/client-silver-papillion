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

use SR\Exception\RuntimeException;

/**
 * Class Order.
 */
class Order
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdOn;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $zip;

    /**
     * @var float
     */
    private $total;

    /**
     * @var float
     */
    private $shipping;

    /**
     * @var float
     */
    private $tax;

    /**
     * @var bool
     */
    private $paid;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var OrderItem[]
     */
    private $items;

    /**
     * @var string|null
     */
    private $couponCode;

    /**
     * @var float|null
     */
    private $couponValue;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->createdOn = new \DateTime();
        $this->paid = false;
    }

    /**
     * @throws RuntimeException
     *
     * @return string
     */
    public function getOrderNumber()
    {
        if (!$this->reference || !$this->id) {
            throw RuntimeException::create('Cannot create order number for entity missing ID and REFERENCE fields.');
        }

        return strtoupper(vsprintf('%s-%s-%s-%s', [
            substr($this->reference, 4, 4),
            substr($this->reference, 12, 4),
            substr($this->reference, 16, 4),
            str_pad($this->id, 4, '0', STR_PAD_LEFT)
        ]));
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn(): \DateTime
    {
        return $this->createdOn;
    }

    /**
     * @param \DateTime $createdOn
     */
    public function setCreatedOn(\DateTime $createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @param \DateTime $createdOn
     */
    public function setDatetime(\DateTime $createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return string
     */
    public function getName(): string
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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getZip(): string
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     */
    public function setZip(string $zip)
    {
        $this->zip = $zip;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @param float $total
     */
    public function setTotal(float $total)
    {
        $this->total = $total;
    }

    /**
     * @return float
     */
    public function getShipping(): float
    {
        return $this->shipping;
    }

    /**
     * @param float $shipping
     */
    public function setShipping(float $shipping)
    {
        $this->shipping = $shipping;
    }

    /**
     * @return float
     */
    public function getTax(): float
    {
        return $this->tax;
    }

    /**
     * @param float $tax
     */
    public function setTax(float $tax)
    {
        $this->tax = $tax;
    }

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->paid;
    }

    /**
     * @param bool $paid
     */
    public function setPaid(bool $paid)
    {
        $this->paid = $paid;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference(string $reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return OrderItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param OrderItem[] $items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return int
     */
    public function getItemCount(): int
    {
        $count = 0;

        foreach ($this->items as $item) {
            $count += $item->getCount();
        }

        return $count;
    }

    /**
     * @return bool
     */
    public function hasCoupon(): bool
    {
        return $this->couponCode !== null && $this->couponValue !== null;
    }

    /**
     * @return string|null
     */
    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }

    /**
     * @param string $couponCode
     */
    public function setCouponCode(string $couponCode)
    {
        $this->couponCode = $couponCode;
    }

    /**
     * @return float|null
     */
    public function getCouponValue(): ?float
    {
        return $this->couponValue;
    }

    /**
     * @param float $couponValue
     */
    public function setCouponValue(float $couponValue)
    {
        $this->couponValue = $couponValue;
    }
}

/* EOF */
