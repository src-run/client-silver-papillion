<?php

/*
 * This file is part of the `src-run/srw-client-silver-papillon` project.
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
    private static $iconTemplateWrap = '<span class="icon-wrap-type-%s icon-wrap-name-%s">%s</span>';

    /**
     * @var string
     */
    private static $iconTemplateItem = '<i class="%s"></i>';

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

        $name = $type.'-'.$icon;
        $html = vsprintf(static::$iconTemplateItem, [
            implode(' ', array_merge((array) $type, (array) ('icon-'.$type), (array) $name, $classes)),
        ]);

        if ($wrap) {
            $html = sprintf(static::$iconTemplateWrap, $type, $name, $html);
        }

        return $html;
    }
}

/* EOF */
