<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Authentication;

/**
 * Class Token.
 */
class Token implements TokenInterface
{
    private $token;

    /**
     * @param string $token
     */
    public function __construct($token)
    {
        $this->setToken($token);
    }

    /**
     * @param string $token
     *
     * @return static
     */
    public static function create($token)
    {
        return new static($token);
    }

    /**
     * @return bool
     */
    public function hasToken()
    {
        return $this->token !== null;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
    }
}

/* EOF */
