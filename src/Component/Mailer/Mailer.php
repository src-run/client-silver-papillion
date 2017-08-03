<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Mailer;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Swift_Mime_Message[]
     */
    private $queue = [];

    /**
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param \Swift_Mime_Message $message
     * @param null                $failedRecipients
     *
     * @return int
     */
    public function send(\Swift_Mime_Message $message, &$failedRecipients = null): int
    {
        return $this->mailer->send($message, $failedRecipients);
    }

    /**
     * @param \Swift_Mime_Message $message
     */
    public function queue(\Swift_Mime_Message $message): void
    {
        $this->queue[] = $message;
    }

    /**
     * @return int
     */
    public function flush(): int
    {
        $sent = 0;

        foreach ($this->queue as $message) {
            $sent += $this->send($message);
        }

        return $sent;
    }

    /**
     * @param string|null $subject
     * @param array       $from
     * @param array       $to
     *
     * @return \Swift_Mime_Message
     */
    public function createMessage(string $subject = null, array $from = [], array $to = []): \Swift_Mime_Message
    {
        $message = \Swift_Message::newInstance();

        if ($subject) {
            $message->setSubject($subject);
        }

        if (0 < count($from)) {
            $message->setFrom($from);
        }

        if (0 < count($to)) {
            $message->setFrom($to);
        }

        return $message;
    }

    public function __destruct()
    {
        $this->flush();
    }
}

/* EOF */
