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
use SR\Silencer\CallSilencerFactory;

class UrlToFileExtensionHelper
{
    /**
     * @var string
     */
    private $systemPath;

    /**
     * @var string
     */
    private $webPath;

    /**
     * @param string $systemPath
     * @param string $webPath
     */
    public function __construct(string $systemPath, string $webPath)
    {
        $this->systemPath = $systemPath;
        $this->webPath = $webPath;
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

        $sysPath = sprintf('%s/fetched/%s.%s', $this->systemPath, $md5 = md5($url), $fileExt);
        $webPath = sprintf('%s/fetched/%s.%s', $this->webPath, $md5, $fileExt);

        if (!is_dir(pathinfo($sysPath, PATHINFO_DIRNAME))) {
            mkdir(pathinfo($sysPath, PATHINFO_DIRNAME), 0777, true);
        }

        if (file_exists($sysPath)) {
            return $webPath;
        }

        $fileContents = @file_get_contents($url);

        if (!$fileContents || (!is_dir($sysDir = pathinfo($sysPath, PATHINFO_DIRNAME)) && !@mkdir($sysDir, 0777, true)) ||
            !file_put_contents($sysPath, $fileContents)) {
            return $url;
        }

        return $webPath;
    }

    /**
     * @param string      $url
     * @param string|null $fileExt
     *
     * @return string
     */
    public function urlToFile2($url, $fileExt = null)
    {
        if ($fileExt === null) {
            $fileExt = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        }

        $sysPath = sprintf('%s/fetched/%s.%s', $this->systemPath, $md5 = md5($url), $fileExt);
        $webPath = sprintf('%s/fetched/%s.%s', $this->webPath, $md5, $fileExt);

        if (!is_dir(pathinfo($sysPath, PATHINFO_DIRNAME))) {
            mkdir(pathinfo($sysPath, PATHINFO_DIRNAME), 0777, true);
        }

        if (file_exists($sysPath)) {
            return $webPath;
        }

        return $this->fetchAndSave($url, $sysPath, $webPath);
    }

    private function fetchAndSave($url, $systemFilePath, $webFilePath)
    {
        $contents = CallSilencerFactory::create(function($url) {
            return file_get_contents($url);
        })->invoke($url);

        if ($contents->isFalse() || empty($contents->getReturn())) {
            return $url;
        }

        if (!is_dir($systemPath = pathinfo($systemFilePath, PATHINFO_DIRNAME))) {
            $mkDir = CallSilencerFactory::create(function ($path) {
                return mkdir($path, 0777, true);
            })->invoke($systemPath);

            if ($mkDir->isFalse()) {
                return $url;
            }
        }

        $writer = CallSilencerFactory::create(function ($path, $contents) {
            return file_put_contents($path, $contents);
        })->invoke($systemFilePath, $contents->getReturn());

        if ($writer->isFalse()) {
            return $url;
        }

        return $webFilePath;
    }
}
