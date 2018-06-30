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

use AppBundle\Manager\ContentBlockManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ContentBlockExtension extends AbstractExtension
{
    public static $cache = [];

    /**
     * @var ContentBlockManager
     */
    private $manager;

    /**
     * ContentBlockExtension constructor.
     *
     * @param ContentBlockManager $manager
     */
    public function __construct(ContentBlockManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return  [
            new TwigFunction('block_title', function (string $name) {
                return $this->blockTitle($name);
            }, ['is_safe' => ['html']]),
            new TwigFunction('block_content', function (string $name) {
                return $this->blockContent($name);
            }, ['is_safe' => ['html']]),
            new TwigFunction('block_props', function (string $name) {
                return $this->blockProps($name);
            }, ['is_safe' => ['html']]),
            new TwigFunction('block_prop', function (string $name, string $index) {
                return $this->blockProp($name, $index);
            }, ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function blockTitle($name)
    {
        return $this->get($name)->getTitle();
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function blockContent($name)
    {
        return $this->get($name)->getContent();
    }

    /**
     * @param string $name
     *
     * @return string[]
     */
    private function blockProps($name)
    {
        $props = [];
        foreach ($this->get($name)->getProperties() as $p) {
            if (1 !== preg_match('{\[([^]]+)\]=}i', $p, $matches)) {
                $props[] = $p;
            }

            list($search, $index) = $matches;
            $props[$index] = str_replace($search, '', $p);
        }

        return $props;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function blockProp($name, $index)
    {
        $properties = $this->blockProps($name);

        if (!array_key_exists($index, $properties)) {
            return null;
        }

        return $properties[$index];
    }

    /**
     * @param string $name
     *
     * @return \AppBundle\Entity\ContentBlock
     */
    private function get($name)
    {
        if (array_key_exists($name, static::$cache)) {
            return static::$cache[$name];
        }

        static::$cache[$name] = $result = $this->manager->get($name);

        return $result;
    }
}
