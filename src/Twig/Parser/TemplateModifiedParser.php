<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig\Parser;

use AppBundle\Twig\Locator\TemplateLocator;

class TemplateModifiedParser extends \Twig_TokenParser
{
    /**
     * @var TemplateLocator
     */
    private $locator;

    /**
     * @param TemplateLocator $locator
     */
    public function __construct(TemplateLocator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @param \Twig_Token $token
     *
     * @throws \Twig_Error_Syntax
     *
     * @return \Twig_Node_Text
     */
    public function parse(\Twig_Token $token): \Twig_Node_Text
    {
        $stream = $this->parser->getStream();
        $format = 'l, F j Y, h:i A';

        if (!$stream->test(\Twig_Token::BLOCK_END_TYPE)) {
            $format = $stream->expect(\Twig_Token::STRING_TYPE)->getValue();
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new \Twig_Node_Text(
            gmdate($format, $this->locator->find($this->parser->getStream()->getSourceContext())->getMTime()),
            $token->getLine()
        );
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return 'file_m_time';
    }
}
