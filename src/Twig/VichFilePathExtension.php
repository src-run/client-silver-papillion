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

use AppBundle\Twig\Helper\VichFilePathExtensionHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class VichFilePathExtension extends AbstractExtension
{
    /**
     * @var VichFilePathExtensionHelper
     */
    private $helper;

    /**
     * @param VichFilePathExtensionHelper $helper
     */
    public function __construct(VichFilePathExtensionHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset_uploaded', [$this->helper, 'assetUploaded']),
            new TwigFunction('asset_product', [$this->helper, 'assetProduct']),
            new TwigFunction('asset_category', [$this->helper, 'assetCategory']),
            new TwigFunction('asset_carousel', [$this->helper, 'assetCarousel']),
        ];
    }
}
