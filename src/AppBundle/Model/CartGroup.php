<?php

/*
 * This file is part of the `src-run/srw-client-silverpapillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Model;

use AppBundle\Entity\Product;

/**
 * Class CartGroup.
 */
class CartGroup
{
    /**
     * @var Product[]
     */
    private $items = [];

    /**
     * @param Session $session
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @return \AppBundle\Entity\Product[]
     */
    public function get()
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function set(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @param Product $product
     */
    public function add(Product $product)
    {
        $this->items[] = $product;
    }

    /**
     * @param Product|null $product
     *
     * @return int
     */
    public function count(Product $product = null)
    {
        return count($this->items);
    }

    /**
     * @return float
     */
    public function total()
    {
        return array_reduce($this->items, function ($carry, Product $p) {
            return $carry + $p->getPrice();
        }, 0);
    }

    /**
     * @deprecated
     *
     * @return Product|mixed
     */
    public function getProduct()
    {
        return $this->items[0];
    }

    /**
     * @return Product
     */
    public function item()
    {
        return $this->items[0];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->items[0]->getName();
    }
}

/* EOF */
