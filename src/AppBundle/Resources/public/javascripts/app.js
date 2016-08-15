
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

  var logger = (function () {
    /**
     * @param {string} type
     * @param {string} message
     */
    function log(type, message) {
      console.log(type.toUpperCase() + ': ' + message); // @todo: write proper implementation for logger!
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
  })();


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

    // expose class with methods to internal functions
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

    // expose class with methods to internal functions
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

    // expose class with methods to internal functions
    return {
      onClick: registerClickEventAction,
      on: registerEventAction
    };
  })(window, jQuery);


  /**
   * CAROUSEL SETUP
   */

  var carouselSetup = (function ($) {

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
    function initCarousel(selector, interval) {
      var $carouselDv = $(selector);
      var carouselEls = $carouselDv.find('.item');

      if ($carouselDv && $carouselDv.length > 0 && carouselEls && carouselEls.length > 0) {
        $carouselDv.carousel({ interval: interval });
        carouselEls.map(setupCarouselItem);
      }
    }

    // expose class with methods to internal functions
    return {
      init: initCarousel
    };
  })(jQuery);


  /**
   * LIGHTBOX SETUP
   */

  var lightBoxSetup = (function ($) {

    function initLightBox() {
      var lightBox = new Lightbox();
      lightBox.load();
    }

    // expose class with methods to internal functions
    return {
      init: initLightBox
    };
  })(jQuery);


  /**
   * SILVER PAPILLON APP
   */

  (function () {

    carouselSetup.init('.carousel', 6000);
    lightBoxSetup.init();

    registerEvent.onClick('.card-product .card', function (event) {
      helpers.followLink(linkResolver.resolve(event.target));
    });

    registerEvent.onClick('.card-product-featured .card', function (event) {
      helpers.followLink(linkResolver.resolve(event.target));
    });

    registerEvent.onClick('.card-product-similar .card', function (event) {
      helpers.followLink(linkResolver.resolve(event.target));
    });

    registerEvent.onClick('.card-category .card', function (event) {
      helpers.followLink(linkResolver.resolve(event.target));
    });

    registerEvent.onClick('.card-feed-main .card', function (event) {
      helpers.followLink(linkResolver.resolve(event.target));
    });

    registerEvent.onClick('.card-feed-photo .card', function (event) {
      helpers.followLink(linkResolver.resolve(event.target));
    });

    registerEvent.onClick('.about-map .card', function (event) {
      helpers.followLink(linkResolver.resolve(event.target));
    });

    registerEvent.onClick('.feed-post', function (event) {
      helpers.followLink(linkResolver.resolve(event.target));
    });

    registerEvent.onClick('.card-feed-attachment .card', function (event) {
      helpers.followLink(linkResolver.resolve(event.target));
    });

    registerEvent.on('show.bs.dropdown', '.dropdown', function(event) {
      $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
    });

    registerEvent.on('hide.bs.dropdown', '.dropdown', function(event) {
      $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
    });

  })();
});

/* EOF */
