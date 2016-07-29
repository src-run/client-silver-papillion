
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
        var predicate = predicate = function(i) {
            return i >= 1;
        };

        images.each(function(i, img) {
            if (predicate(i)) { $(img).remove(); }
        });

        images = $(post).find('.feed-post-attachment-media');
        images.each(function(i, img) {
            var imgWrap = $(img);
            var imgReal = imgWrap.find('img');
            imgReal.addClass('center-block');
            imgReal.addClass('img-responsive');
        });
    });

    $('.feed-post-index').each(function (i, post) {
        $(post).on('click', function(event) {
            var target = $(event.target);

            if (!target || !target.data('href')) {
                return;
            }

            window.location = target.data('href');
        });

        var images = $(post).find('.feed-post-attachment-media');
        var predicate = predicate = function(i) {
            return i >= 1;
        };

        images.each(function(i, img) {
            if (predicate(i)) { $(img).remove(); }
        });

        images = $(post).find('.feed-post-attachment-media');
        images.each(function(i, img) {
            var imgWrap = $(img);
            var imgReal = imgWrap.find('img');

            imgReal.remove();

            imgWrap.css({
                width: (100 / images.length) + '%',
                height: '280px',
                'background-image': 'url('+imgReal.attr('src')+')',
                'background-position': '50% 50%',
                'background-size': 'cover'
            })
        });
    });

    $('.category').each(function(i, c) {
        $(c).on('click', function(event) {
            link = $(event.target).find('a').attr('href');
            window.location = link;
        })
    });

    $('.product').each(function(i, p) {
        $(p).on('click', function(event) {
            var target = $(event.target);
            var link = target.data('href');

            if (!target || !link) {
                return;
            }

            window.location = link;
        });
    });
});

/* EOF */
