
/*
 * This file is part of the `src-run/web-app-grunt` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

'use strict';

class ConfigurationParser
{
  /**
   * Construct our configuration object instance.
   *
   * @param {String} file
   */
  constructor (file) {
    this.configFile = './.gulp/config.json';
    this.configValues = {};
    this.configCached   = {};

    if (file) {
      this.configFile = file;
    }

    this.loadConfig();
  }

  /**
   * Load configuration file.
   *
   * @return {Boolean}
   */
  loadConfig () {
    var fileSystem  = require('fs');
    var fileContent = fileSystem.readFileSync(this.configFile, { encoding : 'utf8' });

    this.configValues = JSON.parse(fileContent);
  }

  /**
   * Get directory path from config.
   *
   * @param {string} idx
   * @param {Array}  opt
   *
   * @returns {*}
   */
  path (idx, opt) {
    return this.value('paths', idx, opt);
  }

  /**
   * Get collection of file paths from config.
   *
   * @param {Array} idxs
   *
   * @returns {Array}
   */
  paths (idxs) {
    return Array.prototype.concat(...idxs.map(this.path.bind(this)));
  }

  /**
   * Get file path from config.
   *
   * @param {string} idx
   * @param {Array}  opt
   *
   * @returns {*}
   */
  file (idx, opt) {
    return this.value('files', idx, opt);
  }

  /**
   * Get collection of file paths from config.
   *
   * @param {Array} idxs
   *
   * @returns {Array}
   */
  files (idxs) {
    return Array.prototype.concat(...idxs.map(this.file.bind(this)));
  }

  /**
   * Get option value from config.
   *
   * @param {string} idx
   * @param {Array}  opt
   *
   * @returns {*}
   */
  option (idx, opt) {
    return this.value('opts', idx, opt);
  }

  /**
   * Get requested value from config.
   *
   * @param {string} ctx
   * @param {string} idx
   * @param {Array}  opt
   *
   * @returns {*}
   */
  value (ctx, idx, opt) {
    var val;

    idx = ConfigurationParser.buildIndex(ctx, idx);
    val = this.lookupCachedValue(idx, opt);

    if (val) {
      return val;
    }

    val = this.lookup(idx);
    val = ConfigurationParser.applyOptions(val, opt);

    this.assignCachedValue(idx, opt, val);

    return val;
  }

  /**
   * Return value from cache if already resolved.
   *
   * @param {string} idx
   * @param {Array}  opt
   *
   * @return {string|Array|null}
   */
  lookupCachedValue (idx, opt) {
    var key = ConfigurationParser.buildCacheIndex(idx, opt);

    if (this.configCached[key]) {
      return this.configCached[key];
    }

    return null;
  }

  /**
   * Add value to cache for later retrieval.
   *
   * @param {string}       idx
   * @param {Array}        opt
   * @param {Array|string} val
   */
  assignCachedValue (idx, opt, val) {
    var key = ConfigurationParser.buildCacheIndex(idx, opt);

    this.configCached[key] = val;
  }

  /**
   * Lookup the config value by index.
   *
   * @param {string} idx
   *
   * @returns {string|Array|Object}
   */
  lookup (idx) {
    return this.resolveReplacements(this.resolveValue(idx));
  }

  /**
   * Find a value through a lookup against it's index.
   *
   * @param {string} idx
   *
   * @returns {string|Array|Object}
   */
  resolveValue (idx) {
    var val = this.configValues;

    idx.split('.').forEach(function (i) {
      val = val[i];

      if (!val) {
        throw new Error('Resolution error for index (' + idx + ') at fragment ' + i);
      }
    });

    return val;
  }

  /**
   * Resolve value placeholders.
   *
   * @param {Array|Object|string} val
   *
   * @returns {Array|Object|string}
   */
  resolveReplacements (val) {
    if (val instanceof Object) {
      return this.resolveReplacementsForObject(val);
    }

    if (val instanceof Array) {
      return this.resolveReplacementsForArray(val);
    }

    return this.resolveReplacementsForScalar(val);
  }

  /**
   * Resolve value placeholders in value string.
   *
   * @param {string} val
   *
   * @returns {string}
   */
  resolveReplacementsForScalar (val) {
    var search;
    var replace;
    var i = 0;
    var maxIterations = 20;
    var parsed = val.toString();

    while (true) {
      search = new RegExp('\\$\{([a-z\.-]+)\}', 'i').exec(parsed);

      if (!search || search.length < 2 || i++ > maxIterations) {
        break;
      }

      replace = this.lookup(search[1]);

      if (replace) {
        parsed = parsed.replace(new RegExp(ConfigurationParser.regexQuote(search[0]), 'g'), replace);
      }
    }

    return parsed;
  }

  /**
   * Resolve value placeholders on each array element.
   *
   * @param {Array} val
   *
   * @returns {Array}
   */
  resolveReplacementsForArray (val) {
    return val.map(function (v) {
      return this.resolveReplacementsForScalar(v);
    }.bind(this));
  }

  /**
   * Resolve value placeholders on each object element.
   *
   * @param {Object} val
   *
   * @returns {Object}
   */
  resolveReplacementsForObject (val) {
    Array.prototype.concat(Object.getOwnPropertyNames(val)).forEach(function (property) {
      var v = val[property];

      if (v instanceof Object) {
        val[property] = this.resolveReplacementsForObject(v);
      } else if (v instanceof Array) {
        val[property] = this.resolveReplacementsForArray(v);
      } else if (typeof v === 'string') {
        val[property] = this.resolveReplacementsForScalar(v);
      }
    }.bind(this));

    return val;
  }

  /**
   * Resolve full index if context is specified.
   *
   * @param {string|null} ctx
   * @param {string}      idx
   *
   * @returns {string}
   */
  static buildIndex (ctx, idx) {
    if (ctx) {
      idx = ctx + '.' + idx;
    }

    return idx;
  }

  /**
   * Create a key for the given context, index, and options.
   *
   * @returns {string}
   */
  static buildCacheIndex () {
    var key = 'cache';

    Array.from(arguments).forEach(function (k, i) {
      key += '__' + i + '_' + JSON.stringify(k);
    });

    return key.replace(/\W/g, '');
  }

  /**
   * Apply options to resolved config value.
   *
   * @param {string|Array} val
   * @param {Array}        opt
   *
   * @returns {*}
   */
  static applyOptions (val, opt) {
    if (val instanceof Array) {
      return ConfigurationParser.applyOptionsOnArray(val, opt);
    }

    return ConfigurationParser.applyOptionsOnScalar(val, opt);
  }

  /**
   * Generate final config value by applying passed options to resolved string.
   *
   * @param {string} val
   * @param {Array}  opt
   *
   * @returns {*}
   */
  static applyOptionsOnScalar (val, opt) {
    if (opt && opt.pre) {
      val = opt.pre + val;
    }

    if (opt && opt.post) {
      val = val + opt.post;
    }

    return val;
  }

  /**
   * Generate final config value by applying passed options to each array element.
   *
   * @param {string} val
   * @param {Array}  opt
   *
   * @returns {*}
   */
  static applyOptionsOnArray (val, opt) {
    return val.map(function (v) {
      return ConfigurationParser.applyOptionsOnScalar(v.toString(), opt);
    });
  }

  /**
   * Prepare value for regex by performing a "regex quote" on it.
   *
   * @param {string} val
   *
   * @returns {*}
   */
  static regexQuote (val) {
    return val.replace(/[-\\^$*+?.()|[\]{}]/g, '\\$&');
  }
}

/* export ConfigurationParser class */
module.exports = ConfigurationParser;

/* EOF */
