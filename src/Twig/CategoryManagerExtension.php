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

use AppBundle\Manager\CategoryManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CategoryManagerExtension extends AbstractExtension
{
    /**
     * @var CategoryManager
     */
    private $manager;

    /**
     * @param CategoryManager $manager
     */
    public function __construct(CategoryManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_categories', function () {
                return $this->manager->getAllByWeight();
            }),
        ];
    }
}
