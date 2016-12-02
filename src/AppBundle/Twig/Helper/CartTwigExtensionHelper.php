<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig\Helper;

use AppBundle\Entity\Product;
use AppBundle\Model\Cart;

/**
 * Class CartTwigExtensionHelper.
 */
class CartTwigExtensionHelper
{
    /**
     * @var Cart
     */
    private $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @return cart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param Product $product
     *
     * @return bool
     */
    public function cartHas(Product $product)
    {
        return $this->cart->has($product);
    }

    /**
     * @return mixed
     */
    public function cartUuid()
    {
        return $this->cart->uuid();
    }

    /**
     * @param Product|null $product
     *
     * @return int
     */
    public function cartCount(Product $product = null)
    {
        return $this->cart->count($product);
    }

    /**
     * @return int
     */
    public function cartTotal()
    {
        return $this->cart->total();
    }

    /**
     * @return int
     */
    public function cartSubTotal()
    {
        return $this->cart->subTotal();
    }

    /**
     * @return int
     */
    public function cartTax()
    {
        return $this->cart->tax();
    }

    /**
     * @return int
     */
    public function cartShipping()
    {
        return $this->cart->shipping();
    }

    /**
     * @return \AppBundle\Entity\Product[]
     */
    public function cartItems()
    {
        return $this->cart->getItems();
    }

    /**
     * @return \AppBundle\Entity\Product[]
     */
    public function cartItemsGrouped()
    {
        return $this->cart->getItemsGrouped();
    }
}

/* EOF */
