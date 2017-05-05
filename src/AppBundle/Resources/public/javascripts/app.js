
/*
 * This file is part of the `src-run/src-app-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * or the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

'use strict';

class Logger {
  static log(type, message) {
    if (!window || !window.console) {
      return;
    }
    console.log(type.toUpperCase() + ': ' + message); // @todo: write proper implementation for logger!
  }

  static logError(message) {
    Logger.log('runtime error', message);
  }

  static logException(exception) {
    Logger.log('caught exception', exception.message);
  }
}

class Link {
  static follow(href) {
    if (href) {
      window.location = href;
    }
  }
}

class LinkResolver {
  static hrefResolverStrategyFindInDataAttrOfPassedEl(el, dataKeys) {
    let link = null;
    let keys = dataKeys !== undefined ? dataKeys : ['href', 'link'];

    for (let i = 0; i < keys.length; i++) {
      link = jQuery(el).data(keys[i]);

      if (link && link.length > 0) {
        return link;
      }
    }

    throw new Error('Link resolve strategy using element data attr failed for: ' + el + ' (found 0)');
  }

  static hrefResolverStrategyFindInHrefAttrOfChildren(el) {
    let links = [];

    jQuery(el).find('a').each(function (i, a) {
      let l = jQuery(a).attr('href');

      if (l && l.length > 0) {
        links.push(l);
      }
    });

    if (links.length > 0 && links.length < 2) {
      return links.pop();
    }

    throw new Error('Link resolve strategy using child anchor href attr failed due to none or too many results for: ' + el + ' (found ' + links.length + ')');
  }

  static hrefResolverStrategyFindInAny(el) {
    try {
      return LinkResolver.hrefResolverStrategyFindInDataAttrOfPassedEl(jQuery(el));
    }
    catch (exception) {}

    try {
      return LinkResolver.hrefResolverStrategyFindInHrefAttrOfChildren(jQuery(el));
    }
    catch (exception) {}

    throw new Error('Link resolve strategy using multi-implementation failed for for: ' + jQuery(el));
  }

  static resolve(el) {
    try {
      return LinkResolver.hrefResolverStrategyFindInAny(el);
    }
    catch (exception) {
      Logger.logException(exception)
    }

    return null;
  }
}

class RegisterEvent {
  static on(type, selector, func) {
    let $els = jQuery(selector);

    if (!$els || $els.length === 0) {
      return null;
    }

    $els.each(function (i, el) {
      jQuery(el).on(type, func);
    });

    return true;
  }

  static onClick(selector, func) {
    return RegisterEvent.on('click', selector, func);
  }
}

class Carousel {
  constructor() {
    this.interval = 6000;
    this.$carousel = jQuery('.carousel');
    this.$els = this.$carousel.find('.item');

    this.setup();
  }

  setup() {
    if (this.$carousel && this.$carousel.length > 0 && this.$els && this.$els.length > 0) {
      this.$carousel.carousel({ interval : this.interval });
      this.$els.map(Carousel.setupItem);
    }
  }

  static setupItem(i, el) {
    let $slide = jQuery(el);
    let image = $slide.children('img');

    if (image.length > 1) {
      Logger.logError('Invalid number of child images under carousel item: ' + $slide);
    }

    $slide.css({
      'background-image'   : 'url(' + image.attr('src') + ')',
      'background-size'    : 'cover',
      'background-position': 'center'
    });

    image.css({ opacity: 0 });
  }
}

class FancyBox {
  constructor() {
    this.confOpts = {
      padding: 0
    };

    this.setup();
  }

  setup() {
    let $elements = jQuery('.feed-attachment-link');

    $elements.fancybox(this.confOpts);
  }
}

class Events {
  constructor() {
    this.initMenuDropDown();
    this.initCardProduct();
    this.initCardProductFeatured();
    this.initCardProductSimilar();
    this.initCardProductRelated();
    this.initCardCategory();
    this.initCardMap();
  }

  initMenuDropDown() {
    RegisterEvent.on('show.bs.dropdown', '.dropdown', function () {
      jQuery(this).find('.dropdown-menu').first().stop(true, true).slideDown();
    });

    RegisterEvent.on('hide.bs.dropdown', '.dropdown', function () {
      jQuery(this).find('.dropdown-menu').first().stop(true, true).slideUp();
    });
  }

  initCardProduct() {
    RegisterEvent.onClick('.card-product .card', function (event) {
      Link.follow(LinkResolver.resolve(event.target));
    });
  }

  initCardProductFeatured() {
    RegisterEvent.onClick('.card-product-featured .card', function (event) {
      Link.follow(LinkResolver.resolve(event.target));
    });
  }

  initCardProductSimilar() {
    RegisterEvent.onClick('.card-product-similar .card', function (event) {
      Link.follow(LinkResolver.resolve(event.target));
    });
  }

  initCardProductRelated() {
    RegisterEvent.onClick('.card-product-related .card', function (event) {
      Link.follow(LinkResolver.resolve(event.target));
    });
  }

  initCardCategory() {
    RegisterEvent.onClick('.card-product-category .card', function (event) {
      Link.follow(LinkResolver.resolve(event.target));
    });
  }

  initCardMap() {
    RegisterEvent.onClick('.about-map .card', function (event) {
      Link.follow(LinkResolver.resolve(event.target));
    });
  }
}

class FeedRequest {
  constructor() {
    this.initFeedItems();
    this.initFeedPhotos();
  }

  makeRequest(href, target, feed) {
    jQuery.ajax({
        url: href,
        dataType: 'html',
        crossDomain: true,
        async: true,
        context: document.body,
        timeout: 120000,
        success: function (data) {
            feed.fadeOut(400, function () {
                target.hide().html(data).fadeIn(800);
                this.remove();
            });
            FeedRequest.hideFeedNavigation();
            FeedRequest.initFancyBox();
        },
        error: function () {
            jQuery(feed.find('.fa-spin')).removeClass('fa-refresh').removeClass('fa-spin').addClass('fa-times');
            jQuery(feed.find('p')).html('An error occured while loading feed photos.');
        }
    });
  }

  static hideFeedNavigation(hide = true) {
    let $target = jQuery('#navbar-nav-feed');
    let className = 'hidden';

    if (hide) {
      $target.removeClass(className);
    } else {
      $target.addClass(className);
    }
  }

  static initFancyBox()
  {
    new FancyBox();
  }

  initFeedFromSelector(selector) {
    let $feed = jQuery(selector);
    let $target = jQuery($feed.data('target'));
    let href = $feed.data('href');

    if ($feed && $target && href) {
      this.makeRequest(href, $target, $feed);
    }
  }

  initFeedItems() {
    this.initFeedFromSelector('#feed-listing-loader');
  }

  initFeedPhotos() {
    this.initFeedFromSelector('#feed-photos-loader');
  }
}

class ToolTips {
  constructor() {
    jQuery('[data-toggle="tooltip"]').tooltip();
  }
}

jQuery(document).ready(() => {
  new Carousel();
  new FancyBox();
  new ToolTips();
  new FeedRequest();
  new Events();
});

/* EOF */
