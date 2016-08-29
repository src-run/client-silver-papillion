
/*
 * This file is part of the `src-run/src-app-silver-papillon` project
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * or the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

$(document).ready(function () {

  'use strict';

  /**
   * SIMPLE LOGGER
   */

  var logger = (function (window) {
    /**
     * @param {string} type
     * @param {string} message
     */
    function log(type, message) {
      if (window.console) {
        console.log(type.toUpperCase() + ': ' + message); // @todo: write proper implementation for logger!
      }
    }

    /**
     * @param {string} message
     */
    function logRuntimeError(message) {
      log('runtime error', message);
    }

    /**
     * @param {Error} exception
     */
    function logCaughtException(exception) {
      log('caught exception', exception.message);
    }

    // expose class with methods to internal functions
    return {
      error: logRuntimeError,
      exception: logCaughtException
    };
  })(window);


  /**
   * GENERAL UTILITY/HELPER FUNCTIONS
   */

  var helpers = (function (window) {
    /**
     * @param {string} to
     */
    function setWindowLocation(to) {
      if (to !== null) {
        window.location = to;
      }
    }

    return {
      followLink: setWindowLocation
    };
  })(window);


  /**
   * HREF RESOLVER STRATEGIES
   */

  var linkResolver = (function (window, $) {
    /**
     * @param   {Element}  el
     * @param   {string[]} dataKeys
     * @throws  {Error}
     * @returns {string}
     */
    function hrefResolverStrategyFindInDataAttrOfPassedEl(el, dataKeys) {
      var link = null;
      var keys = dataKeys !== undefined ? dataKeys : ['href', 'link'];

      for (var i = 0; i < keys.length; i++) {
        link = $(el).data(keys[i]);

        if (link && link.length > 0) {
          return link;
        }
      }

      throw new Error('Link resolve strategy using element data attr failed for: ' + el + ' (found 0)');
    }

    /**
     * @param   {Element} el
     * @throws  {Error}
     * @returns {string}
     */
    function hrefResolverStrategyFindInHrefAttrOfChildren(el) {
      var links = [];

      $(el).find('a').each(function (i, a) {
        var l = $(a).attr('href');

        if (l && l.length > 0) {
          links.push(l);
        }
      });

      if (links.length > 0 && links.length < 2) {
        return links.pop();
      }

      throw new Error('Link resolve strategy using child anchor href attr failed due to none or too many results for: ' + el + ' (found ' + links.length + ')');
    }

    /**
     * @param   {Element|string} el
     * @param   {function[]}     strategies
     * @throws  {Error}
     * @returns {string}
     */
    function hrefResolverStrategyFindInAny(el, strategies) {
      var strategy;

      for (var i = 0; i < strategies.length; i++) {
        strategy = strategies[i];

        try {
          return strategy($(el));
        }
        catch (exception) {
          logger.exception(exception);
        }
      }

      throw new Error('Link resolve strategy using multi-implementation failed for for: ' + $(el));
    }

    /**
     * @param   {Element|string} el
     * @returns {string|null}
     */
    function hrefResolver(el) {
      var strategies = [hrefResolverStrategyFindInDataAttrOfPassedEl, hrefResolverStrategyFindInHrefAttrOfChildren];

      try {
        console.log(hrefResolverStrategyFindInAny(el, strategies));
        return hrefResolverStrategyFindInAny(el, strategies);
      }
      catch (exception) {
        logger.exception(exception);
      }

      return null;
    }

    /**
     * @returns {*[]}
     */
    function getAvailableStrategies() {
      return [linkResolver.strategyFindInAny, linkResolver.strategyFindInDataAttrOfPassedEl, linkResolver.strategyFindInHrefAttrOfChildren];
    }

    return {
      strategyFindInHrefAttrOfChildren: hrefResolverStrategyFindInHrefAttrOfChildren,
      strategyFindInDataAttrOfPassedEl: hrefResolverStrategyFindInDataAttrOfPassedEl,
      strategyFindInAny: hrefResolverStrategyFindInAny,
      strategies: getAvailableStrategies,
      resolve: hrefResolver
    };
  })(window, jQuery);


  /**
   * EVENT REGISTRY
   */

  var registerEvent = (function (window, $) {
    /**
     * @param  {string}   type
     * @param  {string}   selector
     * @param  {function} func
     * @return {null|boolean}
     */
    function registerEventAction(type, selector, func) {
      var $els = $(selector);

      if (!$els || $els.length === 0) {
        return null;
      }

      $els.each(function (i, el) {
        $(el).on(type, func);
      });

      return true;
    }

    /**
     * @param {string}   selector
     * @param {function} func
     * @return {null|boolean}
     */
    function registerClickEventAction(selector, func) {
      return registerEventAction('click', selector, func);
    }

    return {
      onClick: registerClickEventAction,
      on: registerEventAction
    };
  })(window, jQuery);


  /**
   * CAROUSEL SETUP
   */

  var carousel = (function ($) {

    /**
     * @param {integer} i
     * @param {Element} el
     */
    function setupCarouselItem(i, el) {
      var $slide = $(el);
      var image = $slide.children('img');

      if (image.length > 1) {
        logger.error('Invalid number of child images under carousel item: ' + $slide);
      }

      $slide.css({
        'background-image'   : 'url(' + image.attr('src') + ')',
        'background-size'    : 'cover',
        'background-position': 'center'
      });

      image.css({ opacity: 0 });
    }

    /**
     * @param {string} selector
     * @param {number} interval
     */
    function initCarousel() {
      var interval = 6000;
      var $carousel = $('.carousel');
      var els = $carousel.find('.item');

      if ($carousel && $carousel.length > 0 && els && els.length > 0) {
        $carousel.carousel({ interval : interval });
        els.map(setupCarouselItem);
      }
    }

    return {
      init: initCarousel
    };
  })(jQuery);


  /**
   * FANCY BOX SETUP
   */

  var fancyBox = (function ($) {

    var dataName = 'fancybox-initialized';
    var selector = '[data-' + dataName + ']';
    var confOpts = {
      padding : 0,
      beforeLoad : function () {
        var width = this.element.data('fancybox-width');

        if (width) {
          this.width = this.element.data('fancybox-width');
        }
      }
    };

    function findUnInitialized() {
      return $(selector).filter(function () {
        return $(this).data(dataName) === 0;
      });
    }

    function performInitialization(elements) {
      elements.fancybox(confOpts);
    }

    function assignElAsInitialized(elements) {
      elements.each(function () {
        $(this).data(dataName, 1);
      });
    }


    function initFancyBoxOnNewElements() {
      var elements = findUnInitialized();

      performInitialization(elements);
      assignElAsInitialized(elements);
    }

    function initFancyBox() {
      initFancyBoxOnNewElements();
    }

    return {
      init: initFancyBox,
      initNewElements: initFancyBoxOnNewElements
    };
  })(jQuery);


  /**
   * SMOOTH SCROLL SETUP
   */

  var smoothScroll = (function (window, document, $) {

    function initSmoothScroll() {
      $(document)
          .on('click', 'a[href*="#"]', function () {
            if (this.hash && this.pathname === location.pathname) {
              $.bbq.pushState('#/' + this.hash.slice(1));

              return false;
            }
          })
          .ready(function () {
            $(window).bind('hashchange', function () {
              var tgt = location.hash.replace(/^#\/?/, '');

              if (document.getElementById(tgt)) {
                $.smoothScroll({ scrollTarget: '#' + tgt, offset: -100 });
              }
            });

            $(window).trigger('hashchange');
          });
    }

    return {
      init: initSmoothScroll
    };
  })(window, document, jQuery);


  /**
   * EVENTS
   */

  var events = (function () {

    function initMenuDropDown() {
      registerEvent.on('show.bs.dropdown', '.dropdown', function () {
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
      });

      registerEvent.on('hide.bs.dropdown', '.dropdown', function () {
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
      });
    }

    function initCardProduct() {
      registerEvent.onClick('.card-product .card', function (event) {
        helpers.followLink(linkResolver.resolve(event.target));
      });
    }

    function initCardProductFeatured() {
      registerEvent.onClick('.card-product-featured .card', function (event) {
        helpers.followLink(linkResolver.resolve(event.target));
      });
    }

    function initCardProductSimilar() {
      registerEvent.onClick('.card-product-similar .card', function (event) {
        helpers.followLink(linkResolver.resolve(event.target));
      });
    }

    function initCardCategory() {
      registerEvent.onClick('.card-product-category .card', function (event) {
        helpers.followLink(linkResolver.resolve(event.target));
      });
    }

    function initCardMap() {
      registerEvent.onClick('.about-map .card', function (event) {
        helpers.followLink(linkResolver.resolve(event.target));
      });
    }

    function init() {
      initMenuDropDown();
      initCardProduct();
      initCardProductFeatured();
      initCardProductSimilar();
      initCardCategory();
      initCardMap();
    }

    return {
      init: init
    };
  })();


  /**
   * FEED FETCHER
   */

  var feedRequest = (function (document, $, fancyBox) {

    var $nav = $('#navbar-nav-feed');

    /**
     * @param {String} href
     * @param {Object} feed
     */
    function makeRequest(href, feed) {
      $.ajax({
        url     : href,
        context : document.body
      })
          .done(function (data) {
            feed.html(data);
            $nav.removeClass('hidden');
            fancyBox.initNewElements();
          })
          .fail(function () {
            $(feed.find('.fa-spin')).removeClass('fa-refresh').removeClass('fa-spin').addClass('fa-times');
            $(feed.find('p')).html('An error occured while loading feed photos.');
          });
    }

    /**
     * @param {String} selector
     */
    function initFeedFromSelector(selector) {
      var $feed = $(selector);
      var href = $feed.data('href');

      if ($feed && href) {
        makeRequest(href, $feed);
      }
    }

    function initFeedItems() {
      initFeedFromSelector('#feed-listing');
    }

    function initFeedPhotos() {
      initFeedFromSelector('#feed-photos');
    }

    function init() {
      initFeedItems();
      initFeedPhotos();
    }

    return {
      init: init
    };
  })(document, jQuery, fancyBox, events);


  /**
   * TOOLTIPS
   */

  var tooltip = (function ($) {

    function init() {
      $('[data-toggle="tooltip"]').tooltip();
    }

    return {
      init: init
    };
  })(jQuery);


  /**
   * SILVER PAPILLON APP
   */

  (function () {

    smoothScroll.init();
    carousel.init();
    events.init();
    fancyBox.init();
    tooltip.init();
    feedRequest.init();

  })();
});

/* EOF */
