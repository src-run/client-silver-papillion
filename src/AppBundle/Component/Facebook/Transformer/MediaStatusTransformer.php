<?php

/*
 * This file is part of the `src-run/src-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Transformer;

/**
 * Class FormatTransformer.
 */
class FormatTransformer implements TransformerInterface
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public static function transform($data, ...$parameters)
    {
        dump('*************');
        dump($data);
        return $data;
    }
}

/* EOF */
