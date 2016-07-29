
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

    $('#carousel-index').find('.item').each(function (i, item) {
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

    $('.feed-post').each(function (i, post) {
        $(post).on('click', function(event) {
            var target = $(event.target);

            if (!target || !target.data('href')) {
                return;
            }

            window.location = target.data('href');
        });

        var images = $(post).find('.feed-post-attachment-media');
        var count = images.length;

        if (count > 2) {
            count = 2;
        }

        images.each(function(i, img) {
            var imgWrap = $(img);

            if (i > count - 1) {
                imgWrap.remove();
                return;
            }

            var image = imgWrap.find('img');
            image.remove();

            imgWrap.css({
                width: (100 / count) + '%',
                height: '300px',
                'background-image': 'url('+image.attr('src')+')',
                'background-position': '50% 50%',
                'background-size': 'cover'
            })
        });
    });
});

/* EOF */
