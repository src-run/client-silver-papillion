<?php

/*
 * This file is part of the `src-run/srw-client-silverpapillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Authentication;

/**
 * Interface AuthenticationInterface.
 */
interface AuthenticationInterface
{
    /**
     * @return TokenInterface
     */
    public function getAuthorization();

    /**
     * @return int
     */
    public function getPageId();

    /**
     * @param int $pageId
     */
    public function setPageId($pageId);

    /**
     * @return int
     */
    public function getAppId();

    /**
     * @param int $appId
     */
    public function setAppId($appId);

    /**
     * @return string
     */
    public function getAppSecret();

    /**
     * @param string $appSecret
     */
    public function setAppSecret($appSecret);

    /**
     * @return string
     */
    public function getGraphVersion();

    /**
     * @param string $graphVersion
     */
    public function setGraphVersion($graphVersion);
}

/* EOF */
