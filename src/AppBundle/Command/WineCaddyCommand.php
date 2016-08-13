<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class WineCaddyCommand extends ContainerAwareCommand
{
    const URL = 'http://berkeleydesigns.com/product-category/wine-caddies-accessories/page/{$page}/';

    /**
     * @var SymfonyStyle
     */
    protected $io;

    protected $products = [];

    /**
     * @var EntityManager
     */
    protected $em;

    protected function configure()
    {
        $this
            ->setName('sr:products:populate')
            ->setDescription('Fetch wine caddies and populate products.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title(mb_strtoupper('Populating product database'));

        $this->em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $this->removeProducts();

        foreach (range(1, 13) as $page) {
            $this->fetchListPage($page);
        }

        $this->io->table(['Product', 'SKU', 'Image URL', 'Local Image Path'], $this->products);
    }

    protected function removeProducts()
    {
        $repo = $this->em->getRepository(Product::class);
        $all = $repo->findAll();

        foreach ($all as $p) {
            $this->em->remove($p);
        }

        $this->em->flush();
    }

    protected function fetchListPage($page)
    {
        $url = str_replace('{$page}', $page, self::URL);

        $this->io->section('Fetching product listing for page '.$page.' of 13');

        $contents = file_get_contents($url);

        preg_match_all('{<h3 class="name"><a href="(http://berkeleydesigns.com/product/[^/]+/)">([^<]+)</a></h3>}', $contents, $matches);

        $this->io->progressStart(count($matches[0]));

        for($i = 0; $i < count($matches[0]); $i++) {
            $this->fetchProductPage($matches[1][$i], $matches[2][$i]);
            $this->io->progressAdvance(1);
        }

        $this->io->progressFinish();
        $this->em->flush();
    }

    protected function fetchProductPage($url, $name)
    {
        $product[] = preg_replace('{[^a-z0-9- ]}i', '-', ucwords(strtolower($name)));
        $contents = file_get_contents($url);

        preg_match('{SKU: ([a-z0-9]+)}i', $contents, $matches);

        if (!count($matches) > 0) {
            return;
        }

        $product[] = $matches[1];

        preg_match('{<a href="(http://berkeleydesigns.com/wp-content/uploads/[^"]+)"}i', $contents, $matches);

        if (!count($matches) > 0) {
            return;
        }

        $product[] = $matches[1];

        $p = $this->persistProduct($product);
        $product[] = $p->getImage();

        $this->products[] = $product;
    }

    protected function persistProduct($product)
    {
        list($name, $sku,) = $product;

        $image = $this->saveImage($product);

        $catRepo = $this->em->getRepository(Category::class);
        $cat = $catRepo->findOneByName('Wine Caddies');

        $p = new Product();
        $p->setName($name);
        $p->setSku($sku);
        $p->setCreatedOn($dateTime = new \DateTime());
        $p->setUpdatedOn($dateTime);
        $p->setImage($image);
        $p->setPrice(45);
        $p->setCategory($cat);
        $p->setEnabled(false);

        $this->em->persist($p);

        return $p;
    }

    protected function saveImage($product)
    {
        list($name, $sku, $imageUrl) = $product;

        $basePath = $this->getContainer()->getParameter('app.sys_path.root');
        $prodPath = $this->getContainer()->getParameter('app.path.product_images');
        $prodName = preg_replace('{[^a-z0-9-\.]}i', '-', strtolower($sku.'-'.str_replace(' ', '-', $name)).'.'.pathinfo($imageUrl, PATHINFO_EXTENSION));

        $fullPath = $basePath.DIRECTORY_SEPARATOR.$prodPath.DIRECTORY_SEPARATOR.$prodName;

        $contents = file_get_contents($imageUrl);
        file_put_contents($fullPath, $contents);

        return $prodName;
    }
}

/* EOF */
