<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig\Locator;

use SR\Exception\Runtime\RuntimeException;
use SR\Spl\File\SplFileInfo;

/**
 * Class TemplateLocator.
 */
class TemplateLocator
{
    /**
     * @var \Twig_LoaderInterface
     */
    private $loader;

    /**
     * @var string
     */
    private $kernelRootDirectory;

    /**
     * @param \Twig_LoaderInterface $loader
     */
    public function __construct(\Twig_LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param string $kernelRootDirectory
     *
     * @return $this
     */
    public function setKernelRoot(string $kernelRootDirectory)
    {
        $this->kernelRootDirectory = $kernelRootDirectory;

        return $this;
    }

    /**
     * @param \Twig_TokenStream $stream
     *
     * @return SplFileInfo
     */
    public function find(\Twig_TokenStream $stream)
    {
        $templatePath = $stream->getSourceContext()->getPath();

        if (is_null($templatePath) || empty($templatePath)) {
            $templatePath = $this->templatePathFromSourceContextName($stream);
        }

        return new SplFileInfo($templatePath);
    }

    /**
     * @param \Twig_TokenStream $stream
     *
     * @return string
     */
    private function templatePathFromSourceContextName(\Twig_TokenStream $stream)
    {
        $name = $stream->getSourceContext()->getName();

        if (false !== $templatePath = $this->templatePathFromNameUsingBundleSyntax($name)) {
            return $name;
        }

        if (false !== $templatePath = realpath($name)) {
            return $name;
        }

        throw new RuntimeException('Could not resolve real template pathname for %s', $name);
    }

    /**
     * @param string $name
     *
     * @return bool|string
     */
    private function templatePathFromNameUsingBundleSyntax(string $name)
    {
        $bundleSyntax = explode(':', $name);

        if (count($bundleSyntax) !== 3) {
            return false;
        }

        return realpath(
            sprintf('%s/../src/%s/Resources/views/%s/%s', $this->kernelRootDirectory, ...$bundleSyntax)
        );
    }
}

/* EOF */
