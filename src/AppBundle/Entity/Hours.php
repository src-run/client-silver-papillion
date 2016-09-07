<?php

/*
 * This file is part of the `src-run/srw-client-silverpapillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

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
    private $iso8601;

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
     * @return bool
     */
    public function isToday()
    {
        return date('N') == $this->iso8601;
    }

    /**
     * @param int $iso8601
     */
    public function setIso8601($iso8601)
    {
        $this->iso8601 = $iso8601;
    }

    /**
     * @return int
     o     */
    public function getIso8601()
    {
        return $this->iso8601;
    }

    /**
     * @param bool $closed
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
