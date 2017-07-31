<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Authentication;

/**
 * Interface TokenInterface.
 */
interface TokenInterface
{
    /**
     * @param string $token
     */
    public function __construct($token);

    /**
     * @param string $token
     *
     * @return mixed
     */
    public static function create($token);

    /**
     * @return bool
     */
    public function hasToken();

    /**
     * @param string $token
     */
    public function setToken($token);

    /**
     * @return string|null
     */
    public function getToken();
}

/* EOF */
