<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ProductUpdateCommand extends ContainerAwareCommand
{
    /**
     * @var SymfonyStyle
     */
    protected $io;

    /**
     * @var EntityManager
     */
    protected $em;

    protected function configure()
    {
        $this
            ->setName('sr:products:update')
            ->setDescription('Update products.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title(mb_strtoupper('Updating product database'));

        $this->em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $this->updateProducts();
    }

    protected function updateProducts()
    {
        $repo = $this->em->getRepository(Product::class);
        $products = $repo->findAll();

        $this->io->progressStart(count($products));

        foreach ($products as $p) {
            $this->updateProduct($p);
            $this->io->progressAdvance(1);
        }
        $this->io->progressFinish();

        $this->em->flush();
    }

    protected function updateProduct(Product $p)
    {
        $p->setTaxable(true);

        $this->em->persist($p);
    }
}
