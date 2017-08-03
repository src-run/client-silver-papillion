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

use Symfony\Component\Yaml\Yaml;

class Query
{
    /**
     * @var string
     */
    private $sql;

    /**
     * @var array|null
     */
    private $parameters;

    /**
     * @var array|null
     */
    private $types;

    /**
     * @var float
     */
    private $timeStart;

    /**
     * @var float
     */
    private $timeStop;

    /**
     * @param string     $sql
     * @param array|null $parameters
     * @param array|null $types
     * @param bool       $markStart
     */
    public function __construct(string $sql, array $parameters = null, array $types = null, bool $markStart = true)
    {
        $this->sql = $sql;
        $this->parameters = $parameters;
        $this->types = $types;

        if ($markStart) {
            $this->markStart();
        }
    }

    /**
     * @return self
     */
    public function markStart(): self
    {
        $this->timeStart = microtime(true);

        return $this;
    }

    /**
     * @return self
     */
    public function markStop(): self
    {
        $this->timeStop = microtime(true);

        return $this;
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * @return bool
     */
    public function hasParameters(): bool
    {
        return 0 !== count($this->parameters);
    }

    /**
     * @return array|null
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    /**
     * @return bool
     */
    public function hasTypes(): bool
    {
        return 0 !== count($this->types);
    }

    /**
     * @return array|null
     */
    public function getTypes(): ?array
    {
        return $this->types;
    }

    /**
     * @return bool
     */
    public function hasDuration(): bool
    {
        return null !== $this->getDuration();
    }

    /**
     * @return float|null
     */
    public function getDuration(): ?float
    {
        if (!$this->timeStart || !$this->timeStop) {
            return null;
        }

        return $this->timeStop - $this->timeStart;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return Yaml::dump($this->__toArray());
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return [
            'type' => 'query',
            'sql' => $this->sql,
            'parameters' => array_map(function ($parameter) {
                return is_object($parameter) ? var_export($parameter, true): $parameter;
            }, $this->parameters),
            'types' => $this->types,
            'start' => $this->timeStart,
            'stop' => $this->timeStop,
            'time' => $this->getDuration(),
        ];
    }
}
