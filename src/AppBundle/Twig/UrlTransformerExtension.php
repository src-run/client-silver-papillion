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
 * Class UrlTransformerExtension
 */
class UrlTransformerExtension extends \Twig_Extension
{
    /**
     * @return \Twig_Function[]
     */
    public function getFilters()
    {
        return [
            new \Twig_Filter('url_abs_to_rel', [$this, 'urlAbsToRel']),
        ];
    }

    /**
     * @param string $url
     *
     * @return mixed|string
     */
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

    /**
     * @return string
     */
    public function getName()
    {
        return 'url_transformer_extension';
    }
}

/* EOF */
