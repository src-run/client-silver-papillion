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

use Doctrine\ORM\PersistentCollection;
use SR\Exception\Logic\InvalidArgumentException;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\TwigExtension;
use Symfony\Component\VarDumper\VarDumper;

class FormatObjectListExtension extends TwigExtension
{
    public function __construct()
    {
        parent::__construct(new TwigOptionsDefinition(), [], [
            new TwigFunctionDefinition('format_object_list', function (array $list, string $glue = ',', string $format = '%s', string $accessor = '__toString') {
                return $this->formatObjectList($list, $glue, $format, $accessor);
            }),
        ]);
    }

    /**
     * @param array  $list
     * @param string $glue
     * @param string $format
     * @param string $accessor
     *
     * @return string
     */
    private function formatObjectList(array $list, string $glue, string $format, string $accessor): string
    {
        $resolved = array_map(function ($object) use ($accessor, $format) {
            return sprintf($format, $this->resolveObjectString($object, $accessor));
        }, $list);

        return implode($glue, $resolved);
    }

    /**
     * @param $object
     * @param $accessor
     *
     * @return string
     */
    private function resolveObjectString($object, $accessor): string
    {
        if (!is_callable([$object, $accessor])) {
            throw new InvalidArgumentException('Accessor method "%s" not available on object "%s"', $accessor, var_export($object, true));
        }

        return $object->{$accessor}();
    }
}
