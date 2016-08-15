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

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Cart.
 */
class Cart implements \Serializable
{
    /**
     * @var string
     */
    const SESSION_KEY = 'app.cart';

    /**
     * @var float
     */
    const RATE_SHIPPING = 6.40;

    /**
     * @var float
     */
    const RATE_TAX_PERCENTAGE = 0.069;

    /**
     * @var Product[]
     */
    private $items = [];

    /**
     * @var Session
     */
    private $session;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param Session $session
     */
    public function __construct(Session $session, EntityManager $entityManager)
    {
        $this->setSession($session);
        $this->setEntityManager($entityManager);
    }

    /**
     * @param Session $session
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Session $session
     *
     * @return static
     */
    public static function create(Session $session, EntityManager $entityManager)
    {
        if ($session->has(static::SESSION_KEY)) {
            $instance = unserialize($session->get(static::SESSION_KEY));

            if ($instance instanceof Cart) {
                $instance->setSession($session);
                $instance->setEntityManager($entityManager);
                $instance->initialize();

                return $instance;
            }
        }

        return new static($session, $entityManager);
    }

    public function initialize()
    {
        array_map(function (Product $p) {
            try {
                $c = $this->entityManager->merge($p->getCategory());
                if ($c instanceof Category) {
                    $p->setCategory($c);
                }
            }
            catch (\Exception $e)
            {
            }

            return $p;
        }, $this->items);
    }

    /**
     * saver
     */
    public function save()
    {
        $this->session->set(static::SESSION_KEY, serialize($this));
    }

    /**
     * clear
     */
    public function clear()
    {
        $this->items = [];
    }

    /**
     * @return \AppBundle\Entity\Product[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return \AppBundle\Entity\Product[]
     */
    public function getItemsGrouped()
    {
        return array_reduce($this->items, function ($carry, Product $p) {
            if (!isset($carry[$p->getId()])) {
                $carry[$p->getId()] = new CartGroup();
            }

            $carry[$p->getId()]->add($p);

            return $carry;
        }, []);
    }

    /**
     * @param array $items
     */
    public function set(array $items = [])
    {
        $this->items = $items;
    }

    public function rmOne(Product $product)
    {
        $removed = false;

        for($i = 0; $i < count($this->items); $i++) {
            if ($product->getName() === $this->items[$i]->getName() && $removed === false) {
                unset($this->items[$i]);
                $removed = true;
            }
        }

        $this->items = array_values($this->items);
    }

    /**
     * @param Product $product
     */
    public function rm(Product $product)
    {
        $this->items = array_filter($this->items, function (Product $p) use ($product) {
            return $p->getName() !== $product->getName();
        });
    }

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
        return $this->count($product) > 0;
    }

    /**
     * @param Product|null $product
     *
     * @return int
     */
    public function count(Product $product = null) {
        if (!$product) {
            return count($this->items);
        }

        return count(array_filter($this->items, function (Product $p) use ($product) {
            return $p->getName() === $product->getName();
        }));
    }

    /**
     * @return float
     */
    public function subTotal()
    {
        return array_reduce($this->items, function ($carry, Product $p) {
            return $carry + $p->getPrice();
        }, 0);
    }

    /**
     * @return float
     */
    public function tax()
    {
        return array_reduce($this->items, function ($carry, Product $p) {
            return $p->isTaxable() ? $carry + ($p->getPrice() * $p->getTaxableRate()) : $carry;
        }, 0);
    }

    /**
     * @return float
     */
    public function shipping()
    {
        return array_reduce($this->items, function ($carry, Product $p) {
            return $carry + $p->getShippingRate();
        }, 0);
    }

    /**
     * @return float
     */
    public function total()
    {
        return $this->subTotal() + $this->tax() + $this->shipping();
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return igbinary_serialize($this->items);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->items = igbinary_unserialize($serialized);
    }
}

/* EOF */
