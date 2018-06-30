<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Configuration;
use Doctrine\ORM\QueryBuilder;

class ConfigurationRepository extends AbstractRepository
{
    /**
     * @param string $index
     *
     * @return Configuration
     */
    public function findByIndex($index)
    {
        return $this->getResult(function (QueryBuilder $b) use ($index) {
            $b
                ->where('c.index = :index')
                ->setParameter('index', $index);
        }, true);
    }
}

/* EOF */
