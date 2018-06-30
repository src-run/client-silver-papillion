<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig\Helper;

class UrlTransformerExtensionHelper
{
    public function urlAbsToRel($url)
    {
        $return = $relative = parse_url($url, PHP_URL_PATH);
        if (!$relative) {
            return $url;
        }

        $query = parse_url($url, PHP_URL_QUERY);
        if ($query) {
            $return .= '?'.$query;
        }

        $fragment = parse_url($url, PHP_URL_FRAGMENT);
        if ($fragment) {
            $return .= '#'.$fragment;
        }

        return $return;
    }
}
