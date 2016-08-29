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
 * Class UrlToFileExtension
 */
class UrlToFileExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    private $sysDir;

    /**
     * @param string $dir
     */
    public function setSysDir($dir)
    {
        $this->sysDir = $dir;
    }

    /**
     * @var string
     */
    private $webDir;

    /**
     * @param string $dir
     */
    public function setWebDir($dir)
    {
        $this->webDir = $dir;
    }

    /**
     * @return \Twig_Function[]
     */
    public function getFilters()
    {
        return [
            new \Twig_Filter('url_to_file', [$this, 'urlToFile']),
            new \Twig_Filter('cache_url', [$this, 'urlToFile'])
        ];
    }

    /**
     * @param string      $url
     * @param string|null $fileExt
     *
     * @return string
     */
    public function urlToFile($url, $fileExt = null)
    {
        if ($fileExt === null) {
            $fileExt = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        }

        $sysPath = sprintf('%s/fetched/%s.%s', $this->sysDir, $md5 = md5($url), $fileExt);
        $webPath = sprintf('%s/fetched/%s.%s', $this->webDir, $md5, $fileExt);

        if (!file_exists(dirname($sysPath))) {
            @mkdir(dirname($sysPath));
        }

        if (file_exists($sysPath)) {
            return $webPath;
        }

        $fileContents = @file_get_contents($url);

        if (!$fileContents || (!is_dir($sysDir = pathinfo($sysPath, PATHINFO_DIRNAME)) && !@mkdir($sysDir, 0777, true)) ||
            !file_put_contents($sysPath, $fileContents))
        {
            return $url;
        }

        return $webPath;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'url_to_file_extension';
    }
}

/* EOF */
