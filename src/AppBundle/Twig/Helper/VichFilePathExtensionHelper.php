<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig\Helper;

/**
 * Class VichFilePathExtension.
 */
class VichFilePathExtensionHelper
{
    /**
     * @var string[]
     */
    private $paths;

    /**
     * @param string[] $paths
     */
    public function __construct($paths)
    {
        $this->paths = $paths;
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public function assetUploaded($file, $context)
    {
        return $this->paths[$context].'/'.$file;
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public function assetProduct($file)
    {
        return $this->assetUploaded($file, 'product');
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public function assetCategory($file)
    {
        return $this->assetUploaded($file, 'category');
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public function assetCarousel($file)
    {
        return $this->assetUploaded($file, 'carousel');
    }
}

/* EOF */
