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

use AppBundle\Twig\Locator\TemplateLocator;
use AppBundle\Twig\Parser\TemplateModifiedParser;
use Twig\Extension\AbstractExtension;

class TemplateModifiedExtension extends AbstractExtension
{
    /**
     * @var TemplateLocator
     */
    private $locator;

    /**
     * @param TemplateLocator $locator
     */
    public function __construct(TemplateLocator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @return \Twig_TokenParser[]
     */
    public function getTokenParsers()
    {
        return [
            new TemplateModifiedParser($this->locator),
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'modified_extension';
    }
}
