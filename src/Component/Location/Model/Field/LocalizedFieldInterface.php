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

interface LocalizedFieldInterface extends FieldInterface
{
    /**
     * @param string $localization
     *
     * @return self
     */
    public function setLocalization(string $localization): self;

    /**
     * @param string $localization
     *
     * @return bool
     */
    public function hasLocalization(string $localization): bool;

    /**
     * @return string
     */
    public function getLocalization(): string;
}

/* EOF */
