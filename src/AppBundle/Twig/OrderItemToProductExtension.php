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
use AppBundle\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;
use Symfony\Component\VarDumper\VarDumper;

class OrderItemToProductExtension extends TwigExtension
{
    /**
     * @var ProductRepository
     */
    private $repository;

    /**
     * @var Product[]
     */
    private $cache;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository(Product::class);

        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('order_product', function (OrderItem $item) {
                if (isset($this->cache[$item->getSku()])) {
                    return $this->cache[$item->getSku()];
                }

                return $this->cache[$item->getSku()] = $this->repository->findOneBy([
                    'sku' => $item->getSku(),
                ]);
            }),
        ]);
    }
}
