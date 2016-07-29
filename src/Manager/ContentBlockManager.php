<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Manager;

use AppBundle\Entity\ContentBlock;
use AppBundle\Repository\ContentBlockRepository;

/**
 * Class ContentBlockManager.
 */
class ContentBlockManager extends AbstractManager
{
    /**
     * @var string
     */
    const ENTITY = ContentBlock::class;

    /**
     * @return ContentBlockRepository|\Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository(static::ENTITY);
    }

    /**
     * @return ContentBlock[]
     */
    public function get($key)
    {
        return $this->getRepository()->findOneByName($key);
    }
}

/* EOF */
