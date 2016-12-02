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

use AppBundle\Twig\Helper\CartTwigExtensionHelper;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

/**
 * Class CartTwigExtension.
 */
class CartTwigExtension extends TwigExtension
{
    public function __construct(CartTwigExtensionHelper $helper)
    {
        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('get_cart',           [$helper, 'getCart']),
            new TwigFunctionDefinition('cart_has',           [$helper, 'cartHas']),
            new TwigFunctionDefinition('cart_uuid',          [$helper, 'cartUuid']),
            new TwigFunctionDefinition('cart_count',         [$helper, 'cartCount']),
            new TwigFunctionDefinition('cart_total',         [$helper, 'cartTotal']),
            new TwigFunctionDefinition('cart_subTotal',      [$helper, 'cartSubTotal']),
            new TwigFunctionDefinition('cart_tax',           [$helper, 'cartTax']),
            new TwigFunctionDefinition('cart_shipping',      [$helper, 'cartShipping']),
            new TwigFunctionDefinition('cart_items',         [$helper, 'cartItems']),
            new TwigFunctionDefinition('cart_items_grouped', [$helper, 'cartItemsGrouped']),
        ]);
    }
}

/* EOF */
