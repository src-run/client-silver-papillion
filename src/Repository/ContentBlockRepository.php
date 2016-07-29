<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ContentBlock;
use Doctrine\ORM\EntityRepository;

/**
 * Class ContentBlockRepository.
 */
class ContentBlockRepository extends EntityRepository
{
    /**
     * @param string $key
     *
     * @return ContentBlock
     */
    public function findOneByKey($key)
    {
        return $this
            ->createQueryBuilder('c')
            ->where('c.name = :name')
            ->setParameter('name', $key)
            ->getQuery()
            ->getSingleResult();
    }
}

/* EOF */
