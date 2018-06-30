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
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class OrderItemToProductExtension extends AbstractExtension
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
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('order_product', function (OrderItem $item): Product {
                return $this->manager->getBySku($item->getSku());
            }),
        ];
    }
}
