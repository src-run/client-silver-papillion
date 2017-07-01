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

use AppBundle\Entity\Coupon;
use AppBundle\Entity\Product;
use AppBundle\Model\Cart;
use Symfony\Component\VarDumper\VarDumper;

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

    /**
     * @return bool
     */
    public function cartHasDiscount()
    {
        return $this->cart->hasCoupon();
    }

    /**
     * @return int
     */
    public function cartDiscount()
    {
        return $this->cart->couponAmount();
    }

    /**
     * @return string
     */
    public function cartDiscountCode()
    {
        return $this->cart->coupon()->getCode();
    }

    /**
     * @return bool
     */
    public function cartHasDiscountError(): bool
    {
        if ($this->cart->coupon() instanceof Coupon && $this->cart->couponAmount() === 0.0) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function cartDiscountErrorMessage(): string
    {
        return $this->cart->couponErrorMessage();
    }
}

/* EOF */
