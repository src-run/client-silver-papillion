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

use AppBundle\Twig\Helper\UrlTransformerExtensionHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UrlTransformerExtension extends AbstractExtension
{
    /**
     * @var UrlTransformerExtensionHelper
     */
    private $helper;

    /**
     * @param UrlTransformerExtensionHelper $helper
     */
    public function __construct(UrlTransformerExtensionHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('url_abs_to_rel', [$this->helper, 'urlAbsToRel']),
        ];
    }
}
