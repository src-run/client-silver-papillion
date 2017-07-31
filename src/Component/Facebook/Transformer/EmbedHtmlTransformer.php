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

use AppBundle\Component\Facebook\Model\AbstractModel;

/**
 * Class EmbedHtmlTransformer.
 */
class EmbedHtmlTransformer implements TransformerInterface
{
    /**
     * {@inheritdoc}
     *
     * @return AbstractModel
     */
    public static function transform($data, ...$parameters)
    {
        if (false !== preg_match('{src="([^"]+)"}i', $data, $matches)) {
            return $matches[1];
        }

        return $data;
    }
}

/* EOF */
