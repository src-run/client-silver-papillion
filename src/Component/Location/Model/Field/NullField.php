<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Location\Model\Field;

final class NullField implements FieldInterface
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return '';
    }

    /**
     * @return bool
     */
    public function hasValue(): bool
    {
        return false;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return null;
    }
}

/* EOF */
