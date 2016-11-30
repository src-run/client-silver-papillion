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
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;

/**
 * Class ContentBlockExtension.
 */
class ContentBlockExtension extends TwigExtension
{
    public static $cache = [];

    /**
     * @var ContentBlockManager
     */
    private $manager;

    public function __construct()
    {
        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('block_title', [$this, 'blockTitle'], new TwigOptionsDefinition(['is_safe' => ['html']])),
            new TwigFunctionDefinition('block_content', [$this, 'blockContent'], new TwigOptionsDefinition(['is_safe' => ['html']])),
            new TwigFunctionDefinition('block_props', [$this, 'blockProps'], new TwigOptionsDefinition(['is_safe' => ['html']])),
            new TwigFunctionDefinition('block_prop', [$this, 'blockProp'], new TwigOptionsDefinition(['is_safe' => ['html']])),
        ]);
    }

    /**
     * @param ContentBlockManager $manager
     */
    public function setContentBlockManager(ContentBlockManager $manager)
    {
        $this->manager = $manager;
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
