<?php

namespace AppBundle\Entity;

/**
 * Class Hours.
 */
class Hours
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $weight;

    /**
     * @var string
     */
    private $dow;

    /**
     * @var bool
     */
    private $closed;

    /**
     * @var \DateTime
     */
    private $timeOpen;

    /**
     * @var \DateTime
     */
    private $timeClose;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $dow
     *
     * @return Hours
     */
    public function setDow($dow)
    {
        $this->dow = $dow;

        return $this;
    }

    /**
     * @return string
     */
    public function getDow()
    {
        return $this->dow;
    }

    /**
     * @param int $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return int
o     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param boolean $closed
     *
     * @return Hours
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;

        return $this;
    }

    /**
     * @return bool
     */
    public function getClosed()
    {
        return $this->closed;
    }

    /**
     * @return bool
     */
    public function isClosed()
    {
        return $this->closed === true;
    }

    /**
     * @return bool
     */
    public function isDowToday()
    {
        return date('N') == $this->weight;
    }

    /**
     * @param \DateTime $timeOpen
     *
     * @return Hours
     */
    public function setTimeOpen($timeOpen)
    {
        $this->timeOpen = $timeOpen;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTimeOpen()
    {
        return $this->timeOpen;
    }

    /**
     * @param \DateTime $timeClose
     *
     * @return Hours
     */
    public function setTimeClose($timeClose)
    {
        $this->timeClose = $timeClose;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTimeClose()
    {
        return $this->timeClose;
    }
}

/* EOF */
