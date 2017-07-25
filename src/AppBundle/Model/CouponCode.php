<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Model;

class CouponCode
{
    /**
     * @var string|null
     */
    private $code;

    /**
     * @param string|null $code
     */
    public function setCode(string $code = null)
    {
        $this->code = $code;
    }

    /**
     * @return null|string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }
}

/* EOF */
