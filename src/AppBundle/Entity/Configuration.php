<?php

namespace AppBundle\Entity;

/**
 * Class Configuration.
 */
class Configuration
{
    /**
     * @var string
     */
    private $index;

    /**
     * @var string
     */
    private $value;

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param string $index
     * @return Configuration
     */
    public function setIndex($index)
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Configuration
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}

/* EOF */
