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
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

/**
 * Class VichFilePathExtension.
 */
class VichFilePathExtension extends TwigExtension
{

    /**
     * @param VichFilePathExtensionHelper $helper
     */
    public function __construct(VichFilePathExtensionHelper $helper)
    {
        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('asset_uploaded', [$helper, 'assetUploaded']),
            new TwigFunctionDefinition('asset_product', [$helper, 'assetProduct']),
            new TwigFunctionDefinition('asset_category', [$helper, 'assetCategory']),
            new TwigFunctionDefinition('asset_carousel', [$helper, 'assetCarousel']),
        ]);
    }
}

/* EOF */
