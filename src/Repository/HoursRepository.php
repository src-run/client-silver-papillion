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

use AppBundle\Entity\Hours;
use Doctrine\ORM\QueryBuilder;

class HoursRepository extends AbstractRepository
{
    /**
     * @return Hours[]
     */
    public function findAllOrderByIso8601()
    {
        return $this->getResult(function (QueryBuilder $b) {
            $b->orderBy('h.iso8601');
        });
    }
}

/* EOF */
