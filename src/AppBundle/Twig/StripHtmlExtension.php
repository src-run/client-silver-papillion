<?php

/*
 * This file is part of the `src-run/srw-client-silverpapillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

use Symfony\Component\VarDumper\VarDumper;

/**
 * Class StripHtmlExtension.
 */
class StripHtmlExtension extends \Twig_Extension
{
    /**
     * @return \Twig_Filter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_Filter('strip_html', [$this, 'stripHtml']),
        ];
    }

    /**
     * @param string $html
     *
     * @return string
     */
    public function stripHtml(string $html, string $allowed = '<b><string><em><italic><a>')
    {
        VarDumper::dump($html);
        VarDumper::dump(strip_tags($html, $allowed));

        return strip_tags($html, $allowed);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'strip_html_extension';
    }
}

/* EOF */
