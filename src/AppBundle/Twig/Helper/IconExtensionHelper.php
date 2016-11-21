<?php

/*
 * This file is part of the `src-run/srw-client-silverpapillon` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig\Helper;

/**
 * Class IconExtensionHelper.
 */
class IconExtensionHelper
{
    /**
     * @var string
     */
    const TEMPLATE_WRAP = <<<'TPL'
<span class="icon-wrap-type-%s icon-wrap-name-%s">
  %s
</span>
TPL;
    /**
     * @var string
     */
    const TEMPLATE_ICON = <<<'TPL'
<i class="%s"></i>
TPL;

    /**
     * @param string   $icon
     * @param bool     $wrap
     * @param string[] $classes
     *
     * @return string
     */
    public function renderIconIon($icon, $wrap = false, array $classes = [])
    {
        return $this->render('ion', $icon, $wrap, $classes);
    }

    /**
     * @param string   $icon
     * @param bool     $wrap
     * @param string[] $classes
     *
     * @return string
     */
    public function renderIconFa($icon, $wrap = false, array $classes = [])
    {
        return $this->render('fa', $icon, $wrap, $classes);
    }

    /**
     * @param string   $type
     * @param string   $icon
     * @param bool     $wrap
     * @param string[] $classes
     *
     * @return string
     */
    private function render($type, $icon, $wrap = false, array $classes = [])
    {
        if (empty(trim($icon))) {
            return '';
        }

        $cssC = $type.'-'.$icon;
        $html = sprintf(self::TEMPLATE_ICON, implode(' ', array_merge((array) $type, (array) ('icon-'.$type), (array) $cssC, $classes)));

        if ($wrap) {
            $html = sprintf(self::TEMPLATE_WRAP, $type, $cssC, $html);
        }

        return $html;
    }
}

/* EOF */
