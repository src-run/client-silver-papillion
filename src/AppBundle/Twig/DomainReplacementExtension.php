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

use AppBundle\Util\Slugger;
use SR\WonkaBundle\Twig\Definition\TwigFilterDefinition;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;
use Symfony\Component\VarDumper\VarDumper;

class DomainReplacementExtension extends TwigExtension
{
    public function __construct()
    {
        parent::__construct(new TwigOptionsDefinition(), [
            new TwigFilterDefinition('replace_domain', function (string $url, string $domain, string $schema = 'https') {
                $parts = parse_url($url);
                return sprintf('%s://%s%s', $schema, $domain, $parts['path']);
            }),
        ]);
    }
}

/* EOF */
