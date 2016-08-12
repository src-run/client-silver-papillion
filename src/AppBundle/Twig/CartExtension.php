<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

use AppBundle\Entity\Product;
use AppBundle\Model\Cart;

/**
 * Class CartExtension
 */
class CartExtension extends \Twig_Extension
{
    /**
     * @var Cart
     */
    private $cart;

    /**
     * @param Cart $cart
     */
    public function setCart(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @return \Twig_Function[]
     */
    public function getFunctions()
    {
        return array(
            new \Twig_Function('get_cart', [$this, 'getCart']),
            new \Twig_Function('cart_has', [$this, 'cartHas']),
            new \Twig_Function('cart_count', [$this, 'cartCount']),
            new \Twig_Function('cart_total', [$this, 'cartTotal']),
            new \Twig_Function('cart_items', [$this, 'cartItems']),
            new \Twig_Function('cart_items_grouped', [$this, 'cartItemsGrouped']),
        );
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

    /**
     * @return string
     */
    public function getName()
    {
        return 'cart_extension';
    }
}

/* EOF */
