<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
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
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UrlToFileExtension extends AbstractExtension
{
    /**
     * @var UrlToFileExtensionHelper
     */
    private $helper;

    /**
     * @param UrlToFileExtensionHelper $helper
     */
    public function __construct(UrlToFileExtensionHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('url_to_file', [$this->helper, 'urlToFile']),
            new TwigFilter('cache_url', [$this->helper, 'urlToFile']),
        ];
    }
}
