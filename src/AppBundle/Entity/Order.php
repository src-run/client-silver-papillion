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
use AppBundle\Model\Shipment;
use SR\WonkaBundle\Utility\Security\Security;

/**
 * Class Purchase.
 */
class Order
{
    /**
     * @var string
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
     * @var \DateTime|null
     */
    protected $shippedOn;

    /**
     * @var Shipment|null
     */
    protected $shipping;

    /**
     * @var string[]
     */
    protected $shippingAddress;

    /**
     * @var string[]
     */
    protected $billingAddress;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var OrderItem[]
     */
    protected $orderItems;

    /**
     * Assign entity default values.
     */
    public function __construct()
    {
        $this->id = $this->generateId();
        $this->createdOn = new \DateTime();
        $this->updatedOn = new \DateTime();
        $this->orderItems = new ArrayCollection();
    }

    /**
     * @return string
     */
    private function generateId()
    {
        $rand = substr(Security::getRandomHash(), 0, 6);
        $date = date('Ymd');
        $time = date('Hi');

        return sprintf('%s.%s.%s', $date, $time, $rand);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getId();
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
     * @return \DateTime
     */
    public function getShippedOn()
    {
        return $this->shippedOn;
    }

    /**
     * @param \DateTime $shippedOn
     *
     * @return $this
     */
    public function setShippedOn(\DateTime $shippedOn)
    {
        $this->shippedOn = $shippedOn;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShipped()
    {
        return $this->shippedOn !== null;
    }

    /**
     * @param Shipment $shipping
     *
     * @return $this
     */
    public function setShipping(Shipment $shipping)
    {
        $this->shipping = $shipping;

        return $this;
    }

    /**
     * @return Shipment|null
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @return bool
     */
    public function hasShipping()
    {
        return $this->shipping !== null;
    }

    /**
     * @param string[] $address
     *
     * @return $this
     */
    public function setShippingAddress(array $address)
    {
        $this->shippingAddress = $address;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * @param string[] $address
     *
     * @return $this
     */
    public function setBillingAddress(array $address)
    {
        $this->billingAddress = $address;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return OrderItem[]|ArrayCollection
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        $total = 0.0;

        foreach ($this->getOrderItems() as $item) {
            $total += $item->getTotalPrice();
        }

        return (float) $total;
    }
}

/* EOF */
