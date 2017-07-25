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

use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use SR\Exception\Runtime\RuntimeException;
use SR\Util\Info\ClassInfo;
use SR\Util\Transform\StringTransform;

/**
 * Class AbstractRepository.
 */
abstract class AbstractRepository extends EntityRepository
{
    /**
     * @var bool
     */
    const CACHE_ENABLED = true;

    /**
     * @var int
     */
    const DEFAULT_TTL = 1800;

    /**
     * @param callable|null $config
     * @param string|null   $alias
     *
     * @return \Doctrine\ORM\Query
     */
    protected function getQuery(callable $config = null, $alias = null)
    {
        if ($alias === null) {
            $alias = $this->getAliasFromEntityName();
        }

        $builder = $this->createQueryBuilder($alias);

        if (is_callable($config)) {
            call_user_func_array($config, [$builder, $alias]);
        }

        return $builder->getQuery();
    }

    /**
     * @param callable|null $build
     * @param bool          $single
     * @param int|null      $ttl
     *
     * @return mixed
     */
    protected function getResult(callable $build = null, $single = false, $ttl = null)
    {
        $query = $this->getQuery($build);

        if (self::CACHE_ENABLED) {
            $index = $this->getCacheKey($query);
            $cache = $this->getCacheDriver();

            if ($cache->contains($index)) {
                return $cache->fetch($index);
            }
        }

        if ($single === true) {
            $result = $query->getSingleResult();
        } else {
            $result = $query->getResult();
        }

        if (isset($cache) && isset($index)) {
            $cache->save($index, $result, $ttl ?: self::DEFAULT_TTL);
        }

        return $result;
    }

    /**
     * @param Query $query
     *
     * @return string
     */
    private function getCacheKey(Query $query)
    {
        $k = sprintf('silver-papillon-%s-%s-', $this->getClassNameShort(), $query->getDQL());

        foreach ($query->getParameters() as $parameter) {
            $k .= $this->getCacheKeyPartFromParameter($parameter).'-';
        }

        $transformer = new StringTransform(strtolower(substr($k, 0, strlen($k) - 1)));

        return $transformer->toAlphanumericAndDashes();
    }

    /**
     * @param Query\Parameter $parameter
     *
     * @throws ORMException
     *
     * @return string
     */
    private function getCacheKeyPartFromParameter(Query\Parameter $parameter)
    {
        $name = $parameter->getName();
        $value = $parameter->getValue();

        if (is_object($value) && !method_exists($value, '__toString')) {
            throw new RuntimeException('Could not convert parameter to string for doctrin result cache in %s', $this->getClassName());
        }

        return $name.'='.(string) $value;
    }

    /**
     * @return FilesystemCache
     */
    private function getCacheDriver()
    {
        static $cacheDriver;

        if ($cacheDriver === null) {
            $cacheDriver = new FilesystemCache(__DIR__.'/../../../var/cache/dev/repo/');
        }

        return $cacheDriver;
    }

    /**
     * @return string
     */
    private function getAliasFromEntityName()
    {
        return strtolower(substr($this->getClassNameShort(), 0, 1));
    }

    /**
     * @return string
     */
    private function getClassNameShort()
    {
        return ClassInfo::getNameShort($this->getClassName());
    }
}

/* EOF */
