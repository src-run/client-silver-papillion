
/*
 * This file is part of the `src-run/src-app-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * or the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

$(document).ready(function() {
    $('.carousel').carousel({
        interval: 3000
    });

    $('#carousel-index .item').each(function (i, item) {
        item = $(item);
        img = item.children('img');
        src = img.attr('src');
        item.css({
            'background-image': 'url('+src+')',
            'background-size': 'cover',
            'background-position': 'center'
        });
        img.css({
            'opacity': 0
        });
    });
});

/* EOF */
