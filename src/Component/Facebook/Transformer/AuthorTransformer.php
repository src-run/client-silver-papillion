<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Transformer;

class AuthorTransformer implements TransformerInterface
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public static function transform($data, ...$parameters)
    {
        return isset($data['name']) ? $data['name'] : null;
    }
}
