<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig\Locator;

use SR\File\FileInfo;
use SR\Reflection\Inspect;

/**
 * Class TemplateLocator
 */
class TemplateLocator
{
    /**
     * @var \Twig_LoaderInterface
     */
    private $loader;

    /**
     * @param \Twig_LoaderInterface $loader
     */
    public function __construct(\Twig_LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param string $name
     *
     * @return FileInfo|null
     */
    public function find($name)
    {
        try {
            $filePath = Inspect::this($this->loader)
                ->getMethod('findTemplate')
                ->invoke($this->loader, $name);

            return new FileInfo($filePath);
        }
        catch (\Exception $e) {
            return null;
        }
    }
}

/* EOF */
