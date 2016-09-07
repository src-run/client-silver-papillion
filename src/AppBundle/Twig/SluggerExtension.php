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

use AppBundle\Util\Slugger;

/**
 * Class SluggerExtension.
 */
class SluggerExtension extends \Twig_Extension
{
    /**
     * @var Slugger
     */
    private $slugger;

    /**
     * @param Slugger $slugger
     */
    public function setSlugger(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @return \Twig_Filter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_Filter('slugify', [$this, 'slugify']),
        ];
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function slugify($value)
    {
        return $this->slugger->slugify($value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'slugger_extension';
    }
}

/* EOF */
