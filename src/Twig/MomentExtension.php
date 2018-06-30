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

class MomentExtension extends AbstractExtension
{
    /**
     * @var string[]
     */
    private const MOMENT_STRINGS = [
        'just now',
        'moments ago',
        'in the past half-hour',
        'in the past hour',
        'a few hours ago',
        'in the past day',
        'in the past two days',
        'in the past week',
        'in the past two weeks',
        'in the past month',
        'over a month ago',
        'over two months ago',
        'over three months ago',
        'ages ago',
    ];

    /**
     * @var int[]
     */
    private const MOMENT_TIMES = [
        5 * 60,
        10 * 60,
        30 * 60,
        60 * 60,
        4 * 60 * 60,
        24 * 60 * 60,
        2 * 24 * 60 * 60,
        7 * 24 * 60 * 60,
        2 * 7 * 24 * 60 * 60,
        30 * 24 * 60 * 60,
        2 * 30 * 24 * 60 * 60,
        3 * 30 * 24 * 60 * 60,
        4 * 30 * 24 * 60 * 60,
    ];

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('moment', function (\DateTime $ago): string {
                return $this->moment($ago);
            }, ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param \DateTime $ago
     *
     * @return string
     */
    private function moment(\DateTime $ago): string
    {
        $datetime = new \DateTime();
        $deltaAbs = abs($datetime->format('U') - $ago->format('U'));

        $i = 0;

        for ($j = 0; $j < count(self::MOMENT_TIMES); ++$j) {
            $i = $i + ($deltaAbs > self::MOMENT_TIMES[$j] ? 1 : 0);
        }

        return self::MOMENT_STRINGS[$i];
    }
}
