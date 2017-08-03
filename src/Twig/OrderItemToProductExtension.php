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

use AppBundle\Entity\OrderItem;
use AppBundle\Entity\Product;
use AppBundle\Manager\ProductManager;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

class OrderItemToProductExtension extends TwigExtension
{
    /**
     * @var ProductManager
     */
    private $manager;

    /**
     * @param ProductManager $manager
     */
    public function __construct(ProductManager $manager)
    {
        $this->manager = $manager;

        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('order_product', function (OrderItem $item): Product {
                return $this->manager->getBySku($item->getSku());
            }),
        ]);
    }
}
