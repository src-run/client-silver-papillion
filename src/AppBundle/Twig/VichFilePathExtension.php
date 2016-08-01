<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

/**
 * Class VichFilePathExtension
 */
class VichFilePathExtension extends \Twig_Extension
{
    /**
     * @var string[]
     */
    private $paths;

    /**
     * @param string[] $paths
     */
    public function setPaths($paths)
    {
        $this->paths = $paths;
    }

    /**
     * @return \Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('asset_uploaded', [$this, 'assetUploaded']),
            new \Twig_Function('asset_product', [$this, 'assetProduct']),
            new \Twig_Function('asset_category', [$this, 'assetCategory']),
        ];
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public function assetUploaded($file, $context)
    {
        return $this->paths[$context] . '/' . $file;
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
     * @return string
     */
    public function getName()
    {
        return 'vich_file_path_extension';
    }
}

/* EOF */
