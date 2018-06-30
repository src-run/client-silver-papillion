<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

use AppBundle\Model\Cart;
use AppBundle\Twig\Helper\CartTwigExtensionHelper;
use Ramsey\Uuid\UuidInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CartTwigExtension extends AbstractExtension
{
    /**
     * @var CartTwigExtensionHelper
     */
    private $helper;

    /**
     * @param CartTwigExtensionHelper $helper
     */
    public function __construct(CartTwigExtensionHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_cart', function (): Cart {
                return $this->helper->getCart();
            }),
            new TwigFunction('cart_has', function ($product): bool {
                return $this->helper->cartHas($product);
            }),
            new TwigFunction('cart_uuid', function (): string {
                return $this->helper->cartUuid();
            }),
            new TwigFunction('cart_count', function (): int {
                return $this->helper->cartCount();
            }),
            new TwigFunction('cart_total', function (): float {
                return $this->helper->cartTotal();
            }),
            new TwigFunction('cart_subTotal', function (): float {
                return $this->helper->cartSubTotal();
            }),
            new TwigFunction('cart_tax', function (): float {
                return $this->helper->cartTax();
            }),
            new TwigFunction('cart_shipping', function (): float {
                return $this->helper->cartShipping();
            }),
            new TwigFunction('cart_items', function (): array {
                return $this->helper->cartItems();
            }),
            new TwigFunction('cart_items_grouped', function (): array {
                return $this->helper->cartItemsGrouped();
            }),
            new TwigFunction('cart_discount', function (): float {
                return $this->helper->cartDiscount();
            }),
            new TwigFunction('cart_has_discount', function (): bool {
                return $this->helper->cartHasDiscount();
            }),
            new TwigFunction('cart_discount_code', function (): string {
                return $this->helper->cartDiscountCode();
            }),
            new TwigFunction('cart_has_discount_err', function (): bool {
                return $this->helper->cartHasDiscountError();
            }),
            new TwigFunction('cart_discount_err_msg', function (): string {
                return $this->helper->cartDiscountErrorMessage();
            }),
        ];
    }
}
