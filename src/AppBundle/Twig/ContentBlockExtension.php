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

use AppBundle\Manager\ContentBlockManager;

/**
 * Class ContentBlockExtension.
 */
class ContentBlockExtension extends \Twig_Extension
{
    public static $cache = [];

    /**
     * @var ContentBlockManager
     */
    private $manager;

    /**
     * @param ContentBlockManager $manager
     */
    public function setContentBlockManager(ContentBlockManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return \Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('block_title', [$this, 'blockTitle'], ['is_safe' => ['html']]),
            new \Twig_Function('block_content', [$this, 'blockContent'], ['is_safe' => ['html']]),
            new \Twig_Function('block_props', [$this, 'blockProps'], ['is_safe' => ['html']]),
            new \Twig_Function('block_prop', [$this, 'blockProp'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function blockTitle($name)
    {
        return $this->get($name)->getTitle();
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function blockContent($name)
    {
        return $this->get($name)->getContent();
    }

    /**
     * @param string $name
     *
     * @return string[]
     */
    public function blockProps($name)
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
    public function blockProp($name, $index)
    {
        $props = $this->blockProps($name);

        if (!array_key_exists($index, $props)) {
            return null;
        }

        return $props[$index];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'content_block_extension';
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

/* EOF */
