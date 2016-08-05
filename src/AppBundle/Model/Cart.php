<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Model;

use AppBundle\Entity\Product;

/**
 * Class Cart.
 */
class Cart
{
    /**
     * @var Product[]
     */
    private $items = [];

    /**
     * @param Product $product
     */
    public function add(Product $product) {
        $this->items[] = $product;
    }

    /**
     * @param Product $product
     *
     * @return bool
     */
    public function has(Product $product) {
        return in_array($product, $this->items);
    }

    /**
     * @param Product|null $product
     *
     * @return int
     */
    public function count(Product $product = null) {
        if ($product === null) {
            return count($this->items);
        }

        $i = 0;
        for ($j = 0; $j < $this->count(); $j++) {
            if ($this->items[$j] == $product) {
                $i++;
            }
        }

        return $i;
    }
}

/* EOF */
