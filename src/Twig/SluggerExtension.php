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
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SluggerExtension extends AbstractExtension
{
    /**
     * @var Slugger
     */
    private $slugger;

    /**
     * @param Slugger $slugger
     */
    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('slugify', function (string $string) {
                return $this->slugger->slugify($string);
            }),
        ];
    }

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('slugify', function (string $string) {
                return $this->slugger->slugify($string);
            }),
        ];
    }
}
