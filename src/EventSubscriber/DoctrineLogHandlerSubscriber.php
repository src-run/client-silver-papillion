<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\EventSubscriber;

use AppBundle\Component\Doctrine\Logging\QueryLogger;
use Doctrine\DBAL\Connection;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\VarDumper\VarDumper;

class DoctrineLogHandlerSubscriber implements EventSubscriberInterface
{
    /**
     * @var QueryLogger
     */
    private $logger;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param QueryLogger $logger
     */
    public function __construct(QueryLogger $logger, Connection $connection)
    {
        $this->logger = $logger;
        $this->connection = $connection;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', -255],
            ],
            KernelEvents::TERMINATE => [
                ['onKernelTerminate', 2000],
            ],
        ];
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->isMasterRequest()) {
            $this->connection->getConfiguration()->setSQLLogger($this->logger);
        }
    }

    /**
     * @param Event $event
     */
    public function onKernelTerminate(Event $event)
    {
        if (0 !== $this->logger->getCount() && null !== $path = $this->getSavePath()) {
            @file_put_contents($path, $this->logger->__toString());
        }
    }

    /**
     * @return null|string
     */
    private function getSavePath(): ?string
    {
        $rootPath = dirname((new \ReflectionClass(\AppKernel::class))->getFileName());

        if (!$realPath = realpath($rootPath.'/../var/cache/dev')) {
            return null;
        }

        $savePath = $realPath.'/doctrine-logs';

        if (!is_dir($savePath) && !@mkdir($savePath)) {
            return null;
        }

        $t = time();
        $i = 0;

        do {
            $saveFile = $savePath.sprintf("/request-group-%d%'.03d.log", $t, ++$i);
        } while (file_exists($saveFile));

        return $saveFile;
    }
}
