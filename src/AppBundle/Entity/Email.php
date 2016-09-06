<?php

namespace AppBundle\Entity;

use WhiteOctober\SwiftMailerDBBundle\EmailInterface;

/**
 * Class Email.
 */
class Email implements EmailInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $environment;

    /**
     * @var string[]
     */
    private $sentTo;

    /**
     * @var string[]
     */
    private $sentFrom;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var \DateTime
     */
    private $createdOn;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param $message string Serialized \Swift_Mime_Message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->assignExtraFields($message);
        $this->message = $message;

        return $this;
    }

    /**
     * @param $status string
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param $environment string
     *
     * @return $this
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;

        return $this;
    }

    /**
     * @return \string[]
     */
    public function getSentTo(): array
    {
        return $this->sentTo;
    }

    /**
     * @return \string[]
     */
    public function getSentFrom(): array
    {
        return $this->sentFrom;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn(): \DateTime
    {
        return $this->createdOn;
    }

    /**
     * @param $message
     */
    private function assignExtraFields($message)
    {
        $sm = $this->getSwiftMailerInstance($message);
        $this->sentTo = (array) $sm->getTo();
        $this->sentFrom = (array) $sm->getFrom();
        $this->subject = $sm->getSubject();
        $this->createdOn = new \DateTime('@'.$sm->getDate());
    }

    /**
     * @param string $message
     *
     * @return \Swift_Message
     */
    private function getSwiftMailerInstance($message)
    {
        return unserialize($message);
    }
}

/* EOF */
