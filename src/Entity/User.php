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
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Class User.
 */
class User extends BaseUser
{
    /**
     * @var \AppBundle\Entity\Address
     */
    protected $address;

    /**
     * @var Order[]|ArrayCollection
     */
    protected $orders;

    /**
     * Assign entity property default values.
     */
    public function __construct()
    {
        parent::__construct();

        $this->orders = new ArrayCollection();
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection|Order[] $orders
     *
     * @return $this
     */
    public function setOrders(ArrayCollection $orders)
    {
        $this->orders = $orders->filter(function ($order) {
            return $order instanceof Order;
        });

        return $this;
    }

    /**
     * @return Order[]|\Doctrine\Common\Collections\ArrayCollection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param Order $order
     *
     * @return $this
     */
    public function addOrder(Order $order)
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
        }

        return $this;
    }
}

/* EOF */
