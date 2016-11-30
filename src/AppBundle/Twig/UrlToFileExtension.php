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

use AppBundle\Twig\Helper\UrlToFileExtensionHelper;
use SR\WonkaBundle\Twig\Definition\TwigFilterDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

/**
 * Class UrlToFileExtension.
 */
class UrlToFileExtension extends TwigExtension
{
    /**
     * @param UrlToFileExtensionHelper $helper
     */
    public function __construct(UrlToFileExtensionHelper $helper)
    {
        parent::__construct(new TwigOptionsDefinition(), [
            new TwigFilterDefinition('url_to_file', [$helper, 'urlToFile']),
            new TwigFilterDefinition('cache_url', [$helper, 'urlToFile']),
        ]);
    }
}

/* EOF */
