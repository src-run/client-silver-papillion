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

class Search
{
    /**
     * @var string|null
     */
    private $search;

    /**
     * @param string|null $search
     */
    public function setSearch(string $search = null)
    {
        $this->search = $search;
    }

    /**
     * @return null|string
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * @return bool
     */
    public function hasSearch(): bool
    {
        return $this->search !== null;
    }
}

/* EOF */
