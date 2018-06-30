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

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DomainReplacementExtension extends AbstractExtension
{
    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('replace_domain', function (string $url, string $domain, string $schema = 'https') {
                if (false !== $parts = parse_url($url)) {
                    return sprintf('%s://%s%s', $schema, $domain, $parts['path']);
                }

                return null;
            }),
        ];
    }
}
