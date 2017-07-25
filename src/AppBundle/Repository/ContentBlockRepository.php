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

use AppBundle\Entity\ContentBlock;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ContentBlockRepository.
 */
class ContentBlockRepository extends AbstractRepository
{
    /**
     * @param string $name
     *
     * @return ContentBlock
     */
    public function findOneByName($name)
    {
        return $this->getResult(function (QueryBuilder $b) use ($name) {
            $b
                ->where('c.name = :name')
                ->setParameter('name', $name);
        }, true);
    }
}

/* EOF */
