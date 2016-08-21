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
 * Class EmbedHtmlTransformer.
 */
class EmbedHtmlTransformer implements TransformerInterface
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public static function transform($data, ...$parameters)
    {
        if (empty($data) || 1 !== preg_match('{src="([^"]+)"}i', $data, $matches)) {
            return [];
        }

        $return = [
            'html' => $data,
        ];

        if (count($matches) === 2) {
            $return['url'] = $matches[1];
        }

        return $return;
    }
}

/* EOF */
