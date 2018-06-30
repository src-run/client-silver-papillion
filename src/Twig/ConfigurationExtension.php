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

use AppBundle\Manager\ConfigurationManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ConfigurationExtension extends AbstractExtension
{
    /**
     * @var ConfigurationManager
     */
    private $manager;

    /**
     * @param ConfigurationManager $manager
     */
    public function __construct(ConfigurationManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('config_instance', function (string $index) {
                return $this->manager->get($index);
            }),
            new TwigFunction('config', function (string $index, string $default = null) {
                return $this->manager->value($index, $default);
            })
        ];
    }
}
