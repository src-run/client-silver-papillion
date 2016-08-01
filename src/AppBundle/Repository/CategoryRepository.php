<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Category;

/**
 * Class CategoryRepository.
 */
class CategoryRepository extends AbstractRepository
{
    /**
     * @return Category[]
     */
    public function findAll()
    {
        return $this->getResult();
    }
}

/* EOF */
