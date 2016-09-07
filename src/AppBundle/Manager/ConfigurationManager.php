<?php

/*
 * This file is part of the `src-run/srw-client-silverpapillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Configuration;
use AppBundle\Repository\ConfigurationRepository;

/**
 * Class ConfigurationManager.
 */
class ConfigurationManager extends AbstractManager
{
    /**
     * @var string
     */
    const ENTITY = Configuration::class;

    /**
     * @return ConfigurationRepository|\Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository(static::ENTITY);
    }

    /**
     * @param string $index
     *
     * @return Configuration
     */
    public function get($index)
    {
        return $this->getRepository()->findByIndex($index);
    }

    /**
     * @param string     $index
     * @param mixed|null $default
     *
     * @throws \Exception
     *
     * @return string
     */
    public function value($index, $default = null)
    {
        try {
            return $this->get($index)->getValue();
        } catch (\Exception $e) {
            if ($default) {
                return $default;
            }

            throw $e;
        }
    }
}

/* EOF */
