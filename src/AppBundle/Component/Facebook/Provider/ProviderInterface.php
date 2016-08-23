<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Provider;

use AppBundle\Component\Facebook\Authentication\AuthenticationInterface;
use AppBundle\Component\Facebook\Model\Feed\Page\PageFeed;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * Class ProviderInterface.
 */
interface ProviderInterface
{
    /**
     * @param string $id
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getId();

    /**
     * @param AuthenticationInterface $authentication
     */
    public function setAuthentication(AuthenticationInterface $authentication);

    /**
     * @return AuthenticationInterface
     */
    public function getAuthentication();

    /**
     * @param AdapterInterface $cache
     */
    public function setCache(AdapterInterface $cache);

    /**
     * @return AdapterInterface
     */
    public function getCache();

    /**
     * @return string
     */
    public function getCacheTtl();

    /**
     * @param string $cacheTtl
     */
    public function setCacheTtl($cacheTtl);

    /**
     * @return bool
     */
    public function hasCached();

    /**
     * @return PageFeed
     */
    public function getCached();

    /**
     * @return PageFeed
     */
    public function get();

    /**
     * @return bool
     */
    public function has();
}

/* EOF */
