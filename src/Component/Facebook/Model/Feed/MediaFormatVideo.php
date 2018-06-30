<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Component\Facebook\Model\Feed;

class MediaFormatVideo extends MediaFormat
{
    /**
     * @var array[]
     */
    const MAPPING_DEFINITION = [
        'embed_html' => [
            'to_property' => 'embeddableIframe',
        ],
        'picture' => [
            'to_property' => 'link',
        ],
    ];

    /**
     * @var string
     */
    protected $filter;

    /**
     * @var string
     */
    protected $embeddableIframe;

    /**
     * @return string
     */
    public function getEmbeddableIFrameHtml()
    {
        return $this->embeddableIframe;
    }

    /**
     * @return string
     */
    public function getEmbeddableIFrameLink()
    {
        if (false !== preg_match('{src="([^"]+)"}i', $this->embeddableIframe, $matches)) {
            return $matches[1];
        }

        return $this->embeddableIframe;
    }
}
