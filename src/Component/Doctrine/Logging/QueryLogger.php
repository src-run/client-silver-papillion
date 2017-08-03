<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Doctrine\Logging;

use AppBundle\Component\Environment\Environment;
use Doctrine\DBAL\Logging\SQLLogger;
use Symfony\Component\Yaml\Yaml;

class QueryLogger implements SQLLogger
{
    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var Query[]
     */
    private $queries = [];

    /**
     * @var int
     */
    private $activeQuery = 0;

    /**
     * @param Environment $environment
     */
    public function __construct(Environment $environment)
    {
        $this->enabled = $environment->isDevelopment();
    }

    /**
     * @param string     $sql
     * @param array|null $params
     * @param array|null $types
     *
     * @return void
     */
    public function startQuery($sql, array $params = null, array $types = null): void
    {
        if ($this->enabled) {
            $this->queries[++$this->activeQuery] = new Query($sql, $params, $types);
        }

    }

    /**
     * @return void
     */
    public function stopQuery(): void
    {
        if ($this->enabled) {
            $this->queries[$this->activeQuery]->markStop();
        }
    }

    /**
     * @return float
     */
    public function getDuration(): float
    {
        $duration = 0.0;

        foreach ($this->queries as $query) {
            $duration += $query->getDuration();
        }

        return $duration;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return count($this->queries);
    }

    /**
     * @return Query[]
     */
    public function getQueries(): array
    {
        return $this->queries;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return Yaml::dump($this->__toArray(), 4);
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return [
            'type' => 'request-group',
            'duration' => $this->getDuration(),
            'count' => $this->getCount(),
            'queries' => array_map(function (Query $query) {
                return $query->__toArray();
            }, $this->queries),
        ];
    }
}
