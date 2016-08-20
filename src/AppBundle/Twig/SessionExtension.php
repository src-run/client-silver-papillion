<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

/**
 * Class SessionExtension.
 */
class SessionExtension extends \Twig_Extension
{
    /**
     * @return \Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('get_php_session_id', [$this, 'getPhpSessionId']),
        ];
    }

    /**
     * @return string
     */
    public function getPhpSessionId()
    {
        return session_id();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'session_extension';
    }
}

/* EOF */
