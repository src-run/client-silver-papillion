<?php

/*
 * This file is part of the `src-run/srw-client-silverpapillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Transformer;

/**
 * Class AttachmentTargetTransformer.
 */
class AttachmentTargetTransformer implements TransformerInterface
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public static function transform($data, ...$parameters)
    {
        return isset($data['url']) ? $data['url'] : null;
    }
}

/* EOF */
