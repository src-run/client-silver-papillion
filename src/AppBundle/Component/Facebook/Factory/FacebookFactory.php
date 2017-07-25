<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Factory;

use AppBundle\Component\Facebook\Exception\FacebookException;
use Facebook\Facebook;

/**
 * Class FacebookFactory.
 */
class FacebookFactory
{
    /**
     * @var Facebook|null
     */
    private static $facebook;

    /**
     * @param string $appId
     * @param string $appSecret
     * @param string $graphVersion
     *
     * @throws \SR\Exception\ExceptionInterface
     *
     * @return Facebook|null
     */
    public static function create($appId, $appSecret, $graphVersion)
    {
        if (static::$facebook === null) {
            try {
                static::$facebook = new Facebook([
                    'app_id'                => $appId,
                    'app_secret'            => $appSecret,
                    'default_graph_version' => $graphVersion,
                ]);
            } catch (\Exception $e) {
                throw FacebookException::create('Could not create FB instance in factory: %s', $e->getMessage(), $e);
            }
        }

        return static::$facebook;
    }
}

/* EOF */
