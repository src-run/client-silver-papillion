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

use AppBundle\Component\Location\LocationLookup;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

/**
 * Class UserLocationExtension.
 */
class UserLocationExtension extends TwigExtension
{
    public function __construct(LocationLookup $lookup)
    {
        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('client_is_taxable', function () use ($lookup) {
                return $lookup->lookupUsingClientIp()->getRegionName() === 'Connecticut';
            }),
            new TwigFunctionDefinition('client_region_name', function () use ($lookup) {
                return $lookup->lookupUsingClientIp()->getRegionName();
            }),
        ]);
    }
}

/* EOF */
