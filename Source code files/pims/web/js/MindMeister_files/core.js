/*  Prototype JavaScript framework, version 1.6.1
 *  (c) 2005-2009 Sam Stephenson
 *
 *  Prototype is freely distributable under the terms of an MIT-style license.
 *  For details, see the Prototype web site: http://www.prototypejs.org/
 *
 *--------------------------------------------------------------------------*/

var Prototype = {
  Version: '1.6.1',

  Browser: (function(){
    var ua = navigator.userAgent;
    var isOpera = Object.prototype.toString.call(window.opera) == '[object Opera]';
    return {
      IE:             !!window.attachEvent && !isOpera,
      Opera:          isOpera,
      WebKit:         ua.indexOf('AppleWebKit/') > -1,
      Gecko:          ua.indexOf('Gecko') > -1 && ua.indexOf('KHTML') === -1,
      MobileSafari:   /Apple.*Mobile.*Safari/.test(ua)
    }
  })(),

  BrowserFeatures: {
    XPath: !!document.evaluate,
    SelectorsAPI: !!document.querySelector,
    ElementExtensions: (function() {
      var constructor = window.Element || window.HTMLElement;
      return !!(constructor && constructor.prototype);
    })(),
    SpecificElementExtensions: (function() {
      if (typeof window.HTMLDivElement !== 'undefined')
        return true;

      var div = document.createElement('div');
      var form = document.createElement('form');
      var isSupported = false;

      if (div['__proto__'] && (div['__proto__'] !== form['__proto__'])) {
        isSupported = true;
      }

      div = form = null;

      return isSupported;
    })()
  },

  ScriptFragment: '<script[^>]*>([\\S\\s]*?)<\/script>',
  JSONFilter: /^\/\*-secure-([\s\S]*)\*\/\s*$/,

  emptyFunction: function() { },
  K: function(x) { return x }
};

if (Prototype.Browser.MobileSafari)
  Prototype.BrowserFeatures.SpecificElementExtensions = false;


var Abstract = { };


var Try = {
  these: function() {
    var returnValue;

    for (var i = 0, length = arguments.length; i < length; i++) {
      var lambda = arguments[i];
      try {
        returnValue = lambda();
        break;
      } catch (e) { }
    }

    return returnValue;
  }
};

/* Based on Alex Arnell's inheritance implementation. */

var Class = (function() {
  function subclass() {};
  function create() {
    var parent = null, properties = $A(arguments);
    if (Object.isFunction(properties[0]))
      parent = properties.shift();

    function klass() {
      var a = arguments; // ugly Opera 10.50 fix, need to report and FIXME !!!
      this.initialize.apply(this, a);
    }

    Object.extend(klass, Class.Methods);
    klass.superclass = parent;
    klass.subclasses = [];

    if (parent) {
      subclass.prototype = parent.prototype;
      klass.prototype = new subclass;
      parent.subclasses.push(klass);
    }

    for (var i = 0; i < properties.length; i++)
      klass.addMethods(properties[i]);

    if (!klass.prototype.initialize)
      klass.prototype.initialize = Prototype.emptyFunction;

    klass.prototype.constructor = klass;
    return klass;
  }

  function addMethods(source) {
    var ancestor   = this.superclass && this.superclass.prototype;
    var properties = Object.keys(source);

    if (!Object.keys({ toString: true }).length) {
      if (source.toString != Object.prototype.toString)
        properties.push("toString");
      if (source.valueOf != Object.prototype.valueOf)
        properties.push("valueOf");
    }

    for (var i = 0, length = properties.length; i < length; i++) {
      var property = properties[i], value = source[property];
      if (ancestor && Object.isFunction(value) &&
          value.argumentNames().first() == "$super") {
        var method = value;
        value = (function(m) {
          return function() { return ancestor[m].apply(this, arguments); };
        })(property).wrap(method);

        value.valueOf = method.valueOf.bind(method);
        value.toString = method.toString.bind(method);
      }
      this.prototype[property] = value;
    }

    return this;
  }

  return {
    create: create,
    Methods: {
      addMethods: addMethods
    }
  };
})();
(function() {

  var _toString = Object.prototype.toString;

  function extend(destination, source) {
    for (var property in source)
      destination[property] = source[property];
    return destination;
  }

  function inspect(object) {
    try {
      if (isUndefined(object)) return 'undefined';
      if (object === null) return 'null';
      return object.inspect ? object.inspect() : String(object);
    } catch (e) {
      if (e instanceof RangeError) return '...';
      throw e;
    }
  }

  function toJSON(object) {
    var type = typeof object;
    switch (type) {
      case 'undefined':
      case 'function':
      case 'unknown': return;
      case 'boolean': return object.toString();
    }

    if (object === null) return 'null';
    if (object.toJSON) return object.toJSON();
    if (isElement(object)) return;

    var results = [];
    for (var property in object) {
      var value = toJSON(object[property]);
      if (!isUndefined(value))
        results.push(property.toJSON() + ': ' + value);
    }

    return '{' + results.join(', ') + '}';
  }

  function toQueryString(object) {
    return $H(object).toQueryString();
  }

  function toHTML(object) {
    return object && object.toHTML ? object.toHTML() : String.interpret(object);
  }

  function keys(object) {
    var results = [];
    for (var property in object)
      results.push(property);
    return results;
  }

  function values(object) {
    var results = [];
    for (var property in object)
      results.push(object[property]);
    return results;
  }

  function clone(object) {
    return extend({ }, object);
  }

  function isElement(object) {
    return !!(object && object.nodeType == 1);
  }

  function isArray(object) {
    return _toString.call(object) == "[object Array]";
  }


  function isHash(object) {
    return object instanceof Hash;
  }

  function isFunction(object) {
    return typeof object === "function";
  }

  function isString(object) {
    return _toString.call(object) == "[object String]";
  }

  function isNumber(object) {
    return _toString.call(object) == "[object Number]";
  }

  function isUndefined(object) {
    return typeof object === "undefined";
  }

  extend(Object, {
    extend:        extend,
    inspect:       inspect,
    toJSON:        toJSON,
    toQueryString: toQueryString,
    toHTML:        toHTML,
    keys:          keys,
    values:        values,
    clone:         clone,
    isElement:     isElement,
    isArray:       isArray,
    isHash:        isHash,
    isFunction:    isFunction,
    isString:      isString,
    isNumber:      isNumber,
    isUndefined:   isUndefined
  });
})();
Object.extend(Function.prototype, (function() {
  var slice = Array.prototype.slice;

  function update(array, args) {
    var arrayLength = array.length, length = args.length;
    while (length--) array[arrayLength + length] = args[length];
    return array;
  }

  function merge(array, args) {
    array = slice.call(array, 0);
    return update(array, args);
  }

  function argumentNames() {
    var names = this.toString().match(/^[\s\(]*function[^(]*\(([^)]*)\)/)[1]
      .replace(/\/\/.*?[\r\n]|\/\*(?:.|[\r\n])*?\*\//g, '')
      .replace(/\s+/g, '').split(',');
    return names.length == 1 && !names[0] ? [] : names;
  }

  function bind(context) {
    if (arguments.length < 2 && Object.isUndefined(arguments[0])) return this;
    var __method = this, args = slice.call(arguments, 1);
    return function() {
      var a = merge(args, arguments);
      return __method.apply(context, a);
    }
  }

  function bindAsEventListener(context) {
    var __method = this, args = slice.call(arguments, 1);
    return function(event) {
      var a = update([event || window.event], args);
      return __method.apply(context, a);
    }
  }

  function curry() {
    if (!arguments.length) return this;
    var __method = this, args = slice.call(arguments, 0);
    return function() {
      var a = merge(args, arguments);
      return __method.apply(this, a);
    }
  }

  function delay(timeout) {
    var __method = this, args = slice.call(arguments, 1);
    timeout = timeout * 1000
    return window.setTimeout(function() {
      return __method.apply(__method, args);
    }, timeout);
  }

  function defer() {
    var args = update([0.01], arguments);
    return this.delay.apply(this, args);
  }

  function wrap(wrapper) {
    var __method = this;
    return function() {
      var a = update([__method.bind(this)], arguments);
      return wrapper.apply(this, a);
    }
  }

  function methodize() {
    if (this._methodized) return this._methodized;
    var __method = this;
    return this._methodized = function() {
      var a = update([this], arguments);
      return __method.apply(null, a);
    };
  }

  return {
    argumentNames:       argumentNames,
    bind:                bind,
    bindAsEventListener: bindAsEventListener,
    curry:               curry,
    delay:               delay,
    defer:               defer,
    wrap:                wrap,
    methodize:           methodize
  }
})());


Date.prototype.toJSON = function() {
  return '"' + this.getUTCFullYear() + '-' +
    (this.getUTCMonth() + 1).toPaddedString(2) + '-' +
    this.getUTCDate().toPaddedString(2) + 'T' +
    this.getUTCHours().toPaddedString(2) + ':' +
    this.getUTCMinutes().toPaddedString(2) + ':' +
    this.getUTCSeconds().toPaddedString(2) + 'Z"';
};


RegExp.prototype.match = RegExp.prototype.test;

RegExp.escape = function(str) {
  return String(str).replace(/([.*+?^=!:${}()|[\]\/\\])/g, '\\$1');
};
var PeriodicalExecuter = Class.create({
  initialize: function(callback, frequency) {
    this.callback = callback;
    this.frequency = frequency;
    this.currentlyExecuting = false;

    this.registerCallback();
  },

  registerCallback: function() {
    this.timer = setInterval(this.onTimerEvent.bind(this), this.frequency * 1000);
  },

  execute: function() {
    this.callback(this);
  },

  stop: function() {
    if (!this.timer) return;
    clearInterval(this.timer);
    this.timer = null;
  },

  onTimerEvent: function() {
    if (!this.currentlyExecuting) {
      try {
        this.currentlyExecuting = true;
        this.execute();
        this.currentlyExecuting = false;
      } catch(e) {
        this.currentlyExecuting = false;
        throw e;
      }
    }
  }
});
Object.extend(String, {
  interpret: function(value) {
    return value == null ? '' : String(value);
  },
  specialChar: {
    '\b': '\\b',
    '\t': '\\t',
    '\n': '\\n',
    '\f': '\\f',
    '\r': '\\r',
    '\\': '\\\\'
  }
});

Object.extend(String.prototype, (function() {

  function prepareReplacement(replacement) {
    if (Object.isFunction(replacement)) return replacement;
    var template = new Template(replacement);
    return function(match) { return template.evaluate(match) };
  }

  function gsub(pattern, replacement) {
    var result = '', source = this, match;
    replacement = prepareReplacement(replacement);

    if (Object.isString(pattern))
      pattern = RegExp.escape(pattern);

    if (!(pattern.length || pattern.source)) {
      replacement = replacement('');
      return replacement + source.split('').join(replacement) + replacement;
    }

    while (source.length > 0) {
      if (match = source.match(pattern)) {
        result += source.slice(0, match.index);
        result += String.interpret(replacement(match));
        source  = source.slice(match.index + match[0].length);
      } else {
        result += source, source = '';
      }
    }
    return result;
  }

  function sub(pattern, replacement, count) {
    replacement = prepareReplacement(replacement);
    count = Object.isUndefined(count) ? 1 : count;

    return this.gsub(pattern, function(match) {
      if (--count < 0) return match[0];
      return replacement(match);
    });
  }

  function scan(pattern, iterator) {
    this.gsub(pattern, iterator);
    return String(this);
  }

  function truncate(length, truncation) {
    length = length || 30;
    truncation = Object.isUndefined(truncation) ? '...' : truncation;
    return this.length > length ?
      this.slice(0, length - truncation.length) + truncation : String(this);
  }

  function strip() {
    return this.replace(/^\s+/, '').replace(/\s+$/, '');
  }

  function stripTags() {
    return this.replace(/<\w+(\s+("[^"]*"|'[^']*'|[^>])+)?>|<\/\w+>/gi, '');
  }

  function stripScripts() {
    return this.replace(new RegExp(Prototype.ScriptFragment, 'img'), '');
  }

  function extractScripts() {
    var matchAll = new RegExp(Prototype.ScriptFragment, 'img');
    var matchOne = new RegExp(Prototype.ScriptFragment, 'im');
    return (this.match(matchAll) || []).map(function(scriptTag) {
      return (scriptTag.match(matchOne) || ['', ''])[1];
    });
  }

  function evalScripts() {
    return this.extractScripts().map(function(script) { return eval(script) });
  }

  function escapeHTML() {
    return this.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
  }

  function unescapeHTML() {
    return this.stripTags().replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&');
  }


  function toQueryParams(separator) {
    var match = this.strip().match(/([^?#]*)(#.*)?$/);
    if (!match) return { };

    return match[1].split(separator || '&').inject({ }, function(hash, pair) {
      if ((pair = pair.split('='))[0]) {
        var key = decodeURIComponent(pair.shift());
        var value = pair.length > 1 ? pair.join('=') : pair[0];
        if (value != undefined) value = decodeURIComponent(value);

        if (key in hash) {
          if (!Object.isArray(hash[key])) hash[key] = [hash[key]];
          hash[key].push(value);
        }
        else hash[key] = value;
      }
      return hash;
    });
  }

  function toArray() {
    return this.split('');
  }

  function succ() {
    return this.slice(0, this.length - 1) +
      String.fromCharCode(this.charCodeAt(this.length - 1) + 1);
  }

  function times(count) {
    return count < 1 ? '' : new Array(count + 1).join(this);
  }

  function camelize() {
    var parts = this.split('-'), len = parts.length;
    if (len == 1) return parts[0];

    var camelized = this.charAt(0) == '-'
      ? parts[0].charAt(0).toUpperCase() + parts[0].substring(1)
      : parts[0];

    for (var i = 1; i < len; i++)
      camelized += parts[i].charAt(0).toUpperCase() + parts[i].substring(1);

    return camelized;
  }

  function capitalize() {
    return this.charAt(0).toUpperCase() + this.substring(1).toLowerCase();
  }

  function underscore() {
    return this.replace(/::/g, '/')
               .replace(/([A-Z]+)([A-Z][a-z])/g, '$1_$2')
               .replace(/([a-z\d])([A-Z])/g, '$1_$2')
               .replace(/-/g, '_')
               .toLowerCase();
  }

  function dasherize() {
    return this.replace(/_/g, '-');
  }

  function inspect(useDoubleQuotes) {
    var escapedString = this.replace(/[\x00-\x1f\\]/g, function(character) {
      if (character in String.specialChar) {
        return String.specialChar[character];
      }
      return '\\u00' + character.charCodeAt().toPaddedString(2, 16);
    });
    if (useDoubleQuotes) return '"' + escapedString.replace(/"/g, '\\"') + '"';
    return "'" + escapedString.replace(/'/g, '\\\'') + "'";
  }

  function toJSON() {
    return this.inspect(true);
  }

  function unfilterJSON(filter) {
    return this.replace(filter || Prototype.JSONFilter, '$1');
  }

  function isJSON() {
    var str = this;
    if (str.blank()) return false;
    str = this.replace(/\\./g, '@').replace(/"[^"\\\n\r]*"/g, '');
    return (/^[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]*$/).test(str);
  }

  function evalJSON(sanitize) {
    var json = this.unfilterJSON();
    try {
      if (!sanitize || json.isJSON()) return eval('(' + json + ')');
    } catch (e) { }
    throw new SyntaxError('Badly formed JSON string: ' + this.inspect());
  }

  function include(pattern) {
    return this.indexOf(pattern) > -1;
  }

  function startsWith(pattern) {
    return this.indexOf(pattern) === 0;
  }

  function endsWith(pattern) {
    var d = this.length - pattern.length;
    return d >= 0 && this.lastIndexOf(pattern) === d;
  }

  function empty() {
    return this == '';
  }

  function blank() {
    return /^\s*$/.test(this);
  }

  function interpolate(object, pattern) {
    return new Template(this, pattern).evaluate(object);
  }

  return {
    gsub:           gsub,
    sub:            sub,
    scan:           scan,
    truncate:       truncate,
    strip:          String.prototype.trim ? String.prototype.trim : strip,
    stripTags:      stripTags,
    stripScripts:   stripScripts,
    extractScripts: extractScripts,
    evalScripts:    evalScripts,
    escapeHTML:     escapeHTML,
    unescapeHTML:   unescapeHTML,
    toQueryParams:  toQueryParams,
    parseQuery:     toQueryParams,
    toArray:        toArray,
    succ:           succ,
    times:          times,
    camelize:       camelize,
    capitalize:     capitalize,
    underscore:     underscore,
    dasherize:      dasherize,
    inspect:        inspect,
    toJSON:         toJSON,
    unfilterJSON:   unfilterJSON,
    isJSON:         isJSON,
    evalJSON:       evalJSON,
    include:        include,
    startsWith:     startsWith,
    endsWith:       endsWith,
    empty:          empty,
    blank:          blank,
    interpolate:    interpolate
  };
})());

var Template = Class.create({
  initialize: function(template, pattern) {
    this.template = template.toString();
    this.pattern = pattern || Template.Pattern;
  },

  evaluate: function(object) {
    if (object && Object.isFunction(object.toTemplateReplacements))
      object = object.toTemplateReplacements();

    return this.template.gsub(this.pattern, function(match) {
      if (object == null) return (match[1] + '');

      var before = match[1] || '';
      if (before == '\\') return match[2];

      var ctx = object, expr = match[3];
      var pattern = /^([^.[]+|\[((?:.*?[^\\])?)\])(\.|\[|$)/;
      match = pattern.exec(expr);
      if (match == null) return before;

      while (match != null) {
        var comp = match[1].startsWith('[') ? match[2].replace(/\\\\]/g, ']') : match[1];
        ctx = ctx[comp];
        if (null == ctx || '' == match[3]) break;
        expr = expr.substring('[' == match[3] ? match[1].length : match[0].length);
        match = pattern.exec(expr);
      }

      return before + String.interpret(ctx);
    });
  }
});
Template.Pattern = /(^|.|\r|\n)(#\{(.*?)\})/;

var $break = { };

var Enumerable = (function() {
  function each(iterator, context) {
    var index = 0;
    try {
      this._each(function(value) {
        iterator.call(context, value, index++);
      });
    } catch (e) {
      if (e != $break) throw e;
    }
    return this;
  }

  function eachSlice(number, iterator, context) {
    var index = -number, slices = [], array = this.toArray();
    if (number < 1) return array;
    while ((index += number) < array.length)
      slices.push(array.slice(index, index+number));
    return slices.collect(iterator, context);
  }

  function all(iterator, context) {
    iterator = iterator || Prototype.K;
    var result = true;
    this.each(function(value, index) {
      result = result && !!iterator.call(context, value, index);
      if (!result) throw $break;
    });
    return result;
  }

  function any(iterator, context) {
    iterator = iterator || Prototype.K;
    var result = false;
    this.each(function(value, index) {
      if (result = !!iterator.call(context, value, index))
        throw $break;
    });
    return result;
  }

  function collect(iterator, context) {
    iterator = iterator || Prototype.K;
    var results = [];
    this.each(function(value, index) {
      results.push(iterator.call(context, value, index));
    });
    return results;
  }

  function detect(iterator, context) {
    var result;
    this.each(function(value, index) {
      if (iterator.call(context, value, index)) {
        result = value;
        throw $break;
      }
    });
    return result;
  }

  function findAll(iterator, context) {
    var results = [];
    this.each(function(value, index) {
      if (iterator.call(context, value, index))
        results.push(value);
    });
    return results;
  }

  function grep(filter, iterator, context) {
    iterator = iterator || Prototype.K;
    var results = [];

    if (Object.isString(filter))
      filter = new RegExp(RegExp.escape(filter));

    this.each(function(value, index) {
      if (filter.match(value))
        results.push(iterator.call(context, value, index));
    });
    return results;
  }

  function include(object) {
    if (Object.isFunction(this.indexOf))
      if (this.indexOf(object) != -1) return true;

    var found = false;
    this.each(function(value) {
      if (value == object) {
        found = true;
        throw $break;
      }
    });
    return found;
  }

  function inGroupsOf(number, fillWith) {
    fillWith = Object.isUndefined(fillWith) ? null : fillWith;
    return this.eachSlice(number, function(slice) {
      while(slice.length < number) slice.push(fillWith);
      return slice;
    });
  }

  function inject(memo, iterator, context) {
    this.each(function(value, index) {
      memo = iterator.call(context, memo, value, index);
    });
    return memo;
  }

  function invoke(method) {
    var args = $A(arguments).slice(1);
    return this.map(function(value) {
      return value[method].apply(value, args);
    });
  }

  function max(iterator, context) {
    iterator = iterator || Prototype.K;
    var result;
    this.each(function(value, index) {
      value = iterator.call(context, value, index);
      if (result == null || value >= result)
        result = value;
    });
    return result;
  }

  function min(iterator, context) {
    iterator = iterator || Prototype.K;
    var result;
    this.each(function(value, index) {
      value = iterator.call(context, value, index);
      if (result == null || value < result)
        result = value;
    });
    return result;
  }

  function partition(iterator, context) {
    iterator = iterator || Prototype.K;
    var trues = [], falses = [];
    this.each(function(value, index) {
      (iterator.call(context, value, index) ?
        trues : falses).push(value);
    });
    return [trues, falses];
  }

  function pluck(property) {
    var results = [];
    this.each(function(value) {
      results.push(value[property]);
    });
    return results;
  }

  function reject(iterator, context) {
    var results = [];
    this.each(function(value, index) {
      if (!iterator.call(context, value, index))
        results.push(value);
    });
    return results;
  }

  function sortBy(iterator, context) {
    return this.map(function(value, index) {
      return {
        value: value,
        criteria: iterator.call(context, value, index)
      };
    }).sort(function(left, right) {
      var a = left.criteria, b = right.criteria;
      return a < b ? -1 : a > b ? 1 : 0;
    }).pluck('value');
  }

  function toArray() {
    return this.map();
  }

  function zip() {
    var iterator = Prototype.K, args = $A(arguments);
    if (Object.isFunction(args.last()))
      iterator = args.pop();

    var collections = [this].concat(args).map($A);
    return this.map(function(value, index) {
      return iterator(collections.pluck(index));
    });
  }

  function size() {
    return this.toArray().length;
  }

  function inspect() {
    return '#<Enumerable:' + this.toArray().inspect() + '>';
  }









  return {
    each:       each,
    eachSlice:  eachSlice,
    all:        all,
    every:      all,
    any:        any,
    some:       any,
    collect:    collect,
    map:        collect,
    detect:     detect,
    findAll:    findAll,
    select:     findAll,
    filter:     findAll,
    grep:       grep,
    include:    include,
    member:     include,
    inGroupsOf: inGroupsOf,
    inject:     inject,
    invoke:     invoke,
    max:        max,
    min:        min,
    partition:  partition,
    pluck:      pluck,
    reject:     reject,
    sortBy:     sortBy,
    toArray:    toArray,
    entries:    toArray,
    zip:        zip,
    size:       size,
    inspect:    inspect,
    find:       detect
  };
})();
function $A(iterable) {
  if (!iterable) return [];
  if ('toArray' in Object(iterable)) return iterable.toArray();
  var length = iterable.length || 0, results = new Array(length);
  while (length--) results[length] = iterable[length];
  return results;
}

function $w(string) {
  if (!Object.isString(string)) return [];
  string = string.strip();
  return string ? string.split(/\s+/) : [];
}

Array.from = $A;


(function() {
  var arrayProto = Array.prototype,
      slice = arrayProto.slice,
      _each = arrayProto.forEach; // use native browser JS 1.6 implementation if available

  function each(iterator) {
    for (var i = 0, length = this.length; i < length; i++)
      iterator(this[i]);
  }
  if (!_each) _each = each;

  function clear() {
    this.length = 0;
    return this;
  }

  function first() {
    return this[0];
  }

  function last() {
    return this[this.length - 1];
  }

  function compact() {
    return this.select(function(value) {
      return value != null;
    });
  }

  function flatten() {
    return this.inject([], function(array, value) {
      if (Object.isArray(value))
        return array.concat(value.flatten());
      array.push(value);
      return array;
    });
  }

  function without() {
    var values = slice.call(arguments, 0);
    return this.select(function(value) {
      return !values.include(value);
    });
  }

  function reverse(inline) {
    return (inline !== false ? this : this.toArray())._reverse();
  }

  function uniq(sorted) {
    return this.inject([], function(array, value, index) {
      if (0 == index || (sorted ? array.last() != value : !array.include(value)))
        array.push(value);
      return array;
    });
  }

  function intersect(array) {
    return this.uniq().findAll(function(item) {
      return array.detect(function(value) { return item === value });
    });
  }


  function clone() {
    return slice.call(this, 0);
  }

  function size() {
    return this.length;
  }

  function inspect() {
    return '[' + this.map(Object.inspect).join(', ') + ']';
  }

  function toJSON() {
    var results = [];
    this.each(function(object) {
      var value = Object.toJSON(object);
      if (!Object.isUndefined(value)) results.push(value);
    });
    return '[' + results.join(', ') + ']';
  }

  function indexOf(item, i) {
    i || (i = 0);
    var length = this.length;
    if (i < 0) i = length + i;
    for (; i < length; i++)
      if (this[i] === item) return i;
    return -1;
  }

  function lastIndexOf(item, i) {
    i = isNaN(i) ? this.length : (i < 0 ? this.length + i : i) + 1;
    var n = this.slice(0, i).reverse().indexOf(item);
    return (n < 0) ? n : i - n - 1;
  }

  function concat() {
    var array = slice.call(this, 0), item;
    for (var i = 0, length = arguments.length; i < length; i++) {
      item = arguments[i];
      if (Object.isArray(item) && !('callee' in item)) {
        for (var j = 0, arrayLength = item.length; j < arrayLength; j++)
          array.push(item[j]);
      } else {
        array.push(item);
      }
    }
    return array;
  }

  Object.extend(arrayProto, Enumerable);

  if (!arrayProto._reverse)
    arrayProto._reverse = arrayProto.reverse;

  Object.extend(arrayProto, {
    _each:     _each,
    clear:     clear,
    first:     first,
    last:      last,
    compact:   compact,
    flatten:   flatten,
    without:   without,
    reverse:   reverse,
    uniq:      uniq,
    intersect: intersect,
    clone:     clone,
    toArray:   clone,
    size:      size,
    inspect:   inspect,
    toJSON:    toJSON
  });

  var CONCAT_ARGUMENTS_BUGGY = (function() {
    return [].concat(arguments)[0][0] !== 1;
  })(1,2)

  if (CONCAT_ARGUMENTS_BUGGY) arrayProto.concat = concat;

  if (!arrayProto.indexOf) arrayProto.indexOf = indexOf;
  if (!arrayProto.lastIndexOf) arrayProto.lastIndexOf = lastIndexOf;
})();
function $H(object) {
  return new Hash(object);
};

var Hash = Class.create(Enumerable, (function() {
  function initialize(object) {
    this._object = Object.isHash(object) ? object.toObject() : Object.clone(object);
  }

  function _each(iterator) {
    for (var key in this._object) {
      var value = this._object[key], pair = [key, value];
      pair.key = key;
      pair.value = value;
      iterator(pair);
    }
  }

  function set(key, value) {
    return this._object[key] = value;
  }

  function get(key) {
    if (this._object[key] !== Object.prototype[key])
      return this._object[key];
  }

  function unset(key) {
    var value = this._object[key];
    delete this._object[key];
    return value;
  }

  function toObject() {
    return Object.clone(this._object);
  }

  function keys() {
    return this.pluck('key');
  }

  function values() {
    return this.pluck('value');
  }

  function index(value) {
    var match = this.detect(function(pair) {
      return pair.value === value;
    });
    return match && match.key;
  }

  function merge(object) {
    return this.clone().update(object);
  }

  function update(object) {
    return new Hash(object).inject(this, function(result, pair) {
      result.set(pair.key, pair.value);
      return result;
    });
  }

  function toQueryPair(key, value) {
    if (Object.isUndefined(value)) return key;
    return key + '=' + encodeURIComponent(String.interpret(value));
  }

  function toQueryString() {
    return this.inject([], function(results, pair) {
      var key = encodeURIComponent(pair.key), values = pair.value;

      if (values && typeof values == 'object') {
        if (Object.isArray(values))
          return results.concat(values.map(toQueryPair.curry(key)));
      } else results.push(toQueryPair(key, values));
      return results;
    }).join('&');
  }

  function inspect() {
    return '#<Hash:{' + this.map(function(pair) {
      return pair.map(Object.inspect).join(': ');
    }).join(', ') + '}>';
  }

  function toJSON() {
    return Object.toJSON(this.toObject());
  }

  function clone() {
    return new Hash(this);
  }

  return {
    initialize:             initialize,
    _each:                  _each,
    set:                    set,
    get:                    get,
    unset:                  unset,
    toObject:               toObject,
    toTemplateReplacements: toObject,
    keys:                   keys,
    values:                 values,
    index:                  index,
    merge:                  merge,
    update:                 update,
    toQueryString:          toQueryString,
    inspect:                inspect,
    toJSON:                 toJSON,
    clone:                  clone
  };
})());

Hash.from = $H;
Object.extend(Number.prototype, (function() {
  function toColorPart() {
    return this.toPaddedString(2, 16);
  }

  function succ() {
    return this + 1;
  }

  function times(iterator, context) {
    $R(0, this, true).each(iterator, context);
    return this;
  }

  function toPaddedString(length, radix) {
    var string = this.toString(radix || 10);
    return '0'.times(length - string.length) + string;
  }

  function toJSON() {
    return isFinite(this) ? this.toString() : 'null';
  }

  function abs() {
    return Math.abs(this);
  }

  function round() {
    return Math.round(this);
  }

  function ceil() {
    return Math.ceil(this);
  }

  function floor() {
    return Math.floor(this);
  }

  return {
    toColorPart:    toColorPart,
    succ:           succ,
    times:          times,
    toPaddedString: toPaddedString,
    toJSON:         toJSON,
    abs:            abs,
    round:          round,
    ceil:           ceil,
    floor:          floor
  };
})());

function $R(start, end, exclusive) {
  return new ObjectRange(start, end, exclusive);
}

var ObjectRange = Class.create(Enumerable, (function() {
  function initialize(start, end, exclusive) {
    this.start = start;
    this.end = end;
    this.exclusive = exclusive;
  }

  function _each(iterator) {
    var value = this.start;
    while (this.include(value)) {
      iterator(value);
      value = value.succ();
    }
  }

  function include(value) {
    if (value < this.start)
      return false;
    if (this.exclusive)
      return value < this.end;
    return value <= this.end;
  }

  return {
    initialize: initialize,
    _each:      _each,
    include:    include
  };
})());



var Ajax = {
  getTransport: function() {
    return Try.these(
      function() {return new XMLHttpRequest()},
      function() {return new ActiveXObject('Msxml2.XMLHTTP')},
      function() {return new ActiveXObject('Microsoft.XMLHTTP')}
    ) || false;
  },

  activeRequestCount: 0
};

Ajax.Responders = {
  responders: [],

  _each: function(iterator) {
    this.responders._each(iterator);
  },

  register: function(responder) {
    if (!this.include(responder))
      this.responders.push(responder);
  },

  unregister: function(responder) {
    this.responders = this.responders.without(responder);
  },

  dispatch: function(callback, request, transport, json) {
    this.each(function(responder) {
      if (Object.isFunction(responder[callback])) {
        try {
          responder[callback].apply(responder, [request, transport, json]);
        } catch (e) { }
      }
    });
  }
};

Object.extend(Ajax.Responders, Enumerable);

Ajax.Responders.register({
  onCreate:   function() { Ajax.activeRequestCount++ },
  onComplete: function() { Ajax.activeRequestCount-- }
});
Ajax.Base = Class.create({
  initialize: function(options) {
    this.options = {
      method:       'post',
      asynchronous: true,
      contentType:  'application/x-www-form-urlencoded',
      encoding:     'UTF-8',
      parameters:   '',
      evalJSON:     true,
      evalJS:       true
    };
    Object.extend(this.options, options || { });

    this.options.method = this.options.method.toLowerCase();

    if (Object.isString(this.options.parameters))
      this.options.parameters = this.options.parameters.toQueryParams();
    else if (Object.isHash(this.options.parameters))
      this.options.parameters = this.options.parameters.toObject();
  }
});
Ajax.Request = Class.create(Ajax.Base, {
  _complete: false,

  initialize: function($super, url, options) {
    $super(options);
    this.transport = Ajax.getTransport();
    this.request(url);
  },

  request: function(url) {
    this.url = url;
    this.method = this.options.method;
    var params = Object.clone(this.options.parameters);

    if (!['get', 'post'].include(this.method)) {
      params['_method'] = this.method;
      this.method = 'post';
    }

    this.parameters = params;

    if (params = Object.toQueryString(params)) {
      if (this.method == 'get')
        this.url += (this.url.include('?') ? '&' : '?') + params;
      else if (/Konqueror|Safari|KHTML/.test(navigator.userAgent))
        params += '&_=';
    }

    try {
      var response = new Ajax.Response(this);
      if (this.options.onCreate) this.options.onCreate(response);
      Ajax.Responders.dispatch('onCreate', this, response);

      this.transport.open(this.method.toUpperCase(), this.url,
        this.options.asynchronous);

      if (this.options.asynchronous) this.respondToReadyState.bind(this).defer(1);

      this.transport.onreadystatechange = this.onStateChange.bind(this);
      this.setRequestHeaders();

      this.body = this.method == 'post' ? (this.options.postBody || params) : null;
      this.transport.send(this.body);

      /* Force Firefox to handle ready state 4 for synchronous requests */
      if (!this.options.asynchronous && this.transport.overrideMimeType)
        this.onStateChange();

    }
    catch (e) {
      this.dispatchException(e);
    }
  },

  onStateChange: function() {
    var readyState = this.transport.readyState;
    if (readyState > 1 && !((readyState == 4) && this._complete))
      this.respondToReadyState(this.transport.readyState);
  },

  setRequestHeaders: function() {
    var headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'X-Prototype-Version': Prototype.Version,
      'Accept': 'text/javascript, text/html, application/xml, text/xml, */*'
    };

    if (this.method == 'post') {
      headers['Content-type'] = this.options.contentType +
        (this.options.encoding ? '; charset=' + this.options.encoding : '');

      /* Force "Connection: close" for older Mozilla browsers to work
       * around a bug where XMLHttpRequest sends an incorrect
       * Content-length header. See Mozilla Bugzilla #246651.
       */
      if (this.transport.overrideMimeType &&
          (navigator.userAgent.match(/Gecko\/(\d{4})/) || [0,2005])[1] < 2005)
            headers['Connection'] = 'close';
    }

    if (typeof this.options.requestHeaders == 'object') {
      var extras = this.options.requestHeaders;

      if (Object.isFunction(extras.push))
        for (var i = 0, length = extras.length; i < length; i += 2)
          headers[extras[i]] = extras[i+1];
      else
        $H(extras).each(function(pair) { headers[pair.key] = pair.value });
    }

    for (var name in headers)
      this.transport.setRequestHeader(name, headers[name]);
  },

  success: function() {
    var status = this.getStatus();
    return !status || (status >= 200 && status < 300);
  },

  getStatus: function() {
    try {
      return this.transport.status || 0;
    } catch (e) { return 0 }
  },

  respondToReadyState: function(readyState) {
    var state = Ajax.Request.Events[readyState], response = new Ajax.Response(this);

    if (state == 'Complete') {
      try {
        this._complete = true;
        (this.options['on' + response.status]
         || this.options['on' + (this.success() ? 'Success' : 'Failure')]
         || Prototype.emptyFunction)(response, response.headerJSON);
      } catch (e) {
        this.dispatchException(e);
      }

      var contentType = response.getHeader('Content-type');
      if (this.options.evalJS == 'force'
          || (this.options.evalJS && this.isSameOrigin() && contentType
          && contentType.match(/^\s*(text|application)\/(x-)?(java|ecma)script(;.*)?\s*$/i)))
        this.evalResponse();
    }

    try {
      (this.options['on' + state] || Prototype.emptyFunction)(response, response.headerJSON);
      Ajax.Responders.dispatch('on' + state, this, response, response.headerJSON);
    } catch (e) {
      this.dispatchException(e);
    }

    if (state == 'Complete') {
      this.transport.onreadystatechange = Prototype.emptyFunction;
    }
  },

  isSameOrigin: function() {
    var m = this.url.match(/^\s*https?:\/\/[^\/]*/);
    return !m || (m[0] == '#{protocol}//#{domain}#{port}'.interpolate({
      protocol: location.protocol,
      domain: document.domain,
      port: location.port ? ':' + location.port : ''
    }));
  },

  getHeader: function(name) {
    try {
      return this.transport.getResponseHeader(name) || null;
    } catch (e) { return null; }
  },

  evalResponse: function() {
    try {
      return eval((this.transport.responseText || '').unfilterJSON());
    } catch (e) {
      this.dispatchException(e);
    }
  },

  dispatchException: function(exception) {
    (this.options.onException || Prototype.emptyFunction)(this, exception);
    Ajax.Responders.dispatch('onException', this, exception);
  }
});

Ajax.Request.Events =
  ['Uninitialized', 'Loading', 'Loaded', 'Interactive', 'Complete'];








Ajax.Response = Class.create({
  initialize: function(request){
    this.request = request;
    var transport  = this.transport  = request.transport,
        readyState = this.readyState = transport.readyState;

    if((readyState > 2 && !Prototype.Browser.IE) || readyState == 4) {
      this.status       = this.getStatus();
      this.statusText   = this.getStatusText();
      this.responseText = String.interpret(transport.responseText);
      this.headerJSON   = this._getHeaderJSON();
    }

    if(readyState == 4) {
      var xml = transport.responseXML;
      this.responseXML  = Object.isUndefined(xml) ? null : xml;
      this.responseJSON = this._getResponseJSON();
    }
  },

  status:      0,

  statusText: '',

  getStatus: Ajax.Request.prototype.getStatus,

  getStatusText: function() {
    try {
      return this.transport.statusText || '';
    } catch (e) { return '' }
  },

  getHeader: Ajax.Request.prototype.getHeader,

  getAllHeaders: function() {
    try {
      return this.getAllResponseHeaders();
    } catch (e) { return null }
  },

  getResponseHeader: function(name) {
    return this.transport.getResponseHeader(name);
  },

  getAllResponseHeaders: function() {
    return this.transport.getAllResponseHeaders();
  },

  _getHeaderJSON: function() {
    var json = this.getHeader('X-JSON');
    if (!json) return null;
    json = decodeURIComponent(escape(json));
    try {
      return json.evalJSON(this.request.options.sanitizeJSON ||
        !this.request.isSameOrigin());
    } catch (e) {
      this.request.dispatchException(e);
    }
  },

  _getResponseJSON: function() {
    var options = this.request.options;
    if (!options.evalJSON || (options.evalJSON != 'force' &&
      !(this.getHeader('Content-type') || '').include('application/json')) ||
        this.responseText.blank())
          return null;
    try {
      return this.responseText.evalJSON(options.sanitizeJSON ||
        !this.request.isSameOrigin());
    } catch (e) {
      this.request.dispatchException(e);
    }
  }
});

Ajax.Updater = Class.create(Ajax.Request, {
  initialize: function($super, container, url, options) {
    this.container = {
      success: (container.success || container),
      failure: (container.failure || (container.success ? null : container))
    };

    options = Object.clone(options);
    var onComplete = options.onComplete;
    options.onComplete = (function(response, json) {
      this.updateContent(response.responseText);
      if (Object.isFunction(onComplete)) onComplete(response, json);
    }).bind(this);

    $super(url, options);
  },

  updateContent: function(responseText) {
    var receiver = this.container[this.success() ? 'success' : 'failure'],
        options = this.options;

    if (!options.evalScripts) responseText = responseText.stripScripts();

    if (receiver = $(receiver)) {
      if (options.insertion) {
        if (Object.isString(options.insertion)) {
          var insertion = { }; insertion[options.insertion] = responseText;
          receiver.insert(insertion);
        }
        else options.insertion(receiver, responseText);
      }
      else receiver.update(responseText);
    }
  }
});

Ajax.PeriodicalUpdater = Class.create(Ajax.Base, {
  initialize: function($super, container, url, options) {
    $super(options);
    this.onComplete = this.options.onComplete;

    this.frequency = (this.options.frequency || 2);
    this.decay = (this.options.decay || 1);

    this.updater = { };
    this.container = container;
    this.url = url;

    this.start();
  },

  start: function() {
    this.options.onComplete = this.updateComplete.bind(this);
    this.onTimerEvent();
  },

  stop: function() {
    this.updater.options.onComplete = undefined;
    clearTimeout(this.timer);
    (this.onComplete || Prototype.emptyFunction).apply(this, arguments);
  },

  updateComplete: function(response) {
    if (this.options.decay) {
      this.decay = (response.responseText == this.lastText ?
        this.decay * this.options.decay : 1);

      this.lastText = response.responseText;
    }
    this.timer = this.onTimerEvent.bind(this).delay(this.decay * this.frequency);
  },

  onTimerEvent: function() {
    this.updater = new Ajax.Updater(this.container, this.url, this.options);
  }
});



function $(element) {
  if (arguments.length > 1) {
    for (var i = 0, elements = [], length = arguments.length; i < length; i++)
      elements.push($(arguments[i]));
    return elements;
  }
  if (Object.isString(element))
    element = document.getElementById(element);
  return Element.extend(element);
}

if (Prototype.BrowserFeatures.XPath) {
  document._getElementsByXPath = function(expression, parentElement) {
    var results = [];
    var query = document.evaluate(expression, $(parentElement) || document,
      null, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null);
    for (var i = 0, length = query.snapshotLength; i < length; i++)
      results.push(Element.extend(query.snapshotItem(i)));
    return results;
  };
}

/*--------------------------------------------------------------------------*/

if (!window.Node) var Node = { };

if (!Node.ELEMENT_NODE) {
  Object.extend(Node, {
    ELEMENT_NODE: 1,
    ATTRIBUTE_NODE: 2,
    TEXT_NODE: 3,
    CDATA_SECTION_NODE: 4,
    ENTITY_REFERENCE_NODE: 5,
    ENTITY_NODE: 6,
    PROCESSING_INSTRUCTION_NODE: 7,
    COMMENT_NODE: 8,
    DOCUMENT_NODE: 9,
    DOCUMENT_TYPE_NODE: 10,
    DOCUMENT_FRAGMENT_NODE: 11,
    NOTATION_NODE: 12
  });
}


(function(global) {

  var SETATTRIBUTE_IGNORES_NAME = (function(){
    var elForm = document.createElement("form");
    var elInput = document.createElement("input");
    var root = document.documentElement;
    elInput.setAttribute("name", "test");
    elForm.appendChild(elInput);
    root.appendChild(elForm);
    var isBuggy = elForm.elements
      ? (typeof elForm.elements.test == "undefined")
      : null;
    root.removeChild(elForm);
    elForm = elInput = null;
    return isBuggy;
  })();

  var element = global.Element;
  global.Element = function(tagName, attributes) {
    attributes = attributes || { };
    tagName = tagName.toLowerCase();
    var cache = Element.cache;
    if (SETATTRIBUTE_IGNORES_NAME && attributes.name) {
      tagName = '<' + tagName + ' name="' + attributes.name + '">';
      delete attributes.name;
      return Element.writeAttribute(document.createElement(tagName), attributes);
    }
    if (!cache[tagName]) cache[tagName] = Element.extend(document.createElement(tagName));
    return Element.writeAttribute(cache[tagName].cloneNode(false), attributes);
  };
  Object.extend(global.Element, element || { });
  if (element) global.Element.prototype = element.prototype;
})(this);

Element.cache = { };
Element.idCounter = 1;

Element.Methods = {
  visible: function(element) {
    return $(element).style.display != 'none';
  },

  toggle: function(element) {
    element = $(element);
    Element[Element.visible(element) ? 'hide' : 'show'](element);
    return element;
  },


  hide: function(element) {
    element = $(element);
    element.style.display = 'none';
    return element;
  },

  show: function(element) {
    element = $(element);
    element.style.display = '';
    return element;
  },

  remove: function(element) {
    element = $(element);
    element.parentNode.removeChild(element);
    return element;
  },

  update: (function(){

    var SELECT_ELEMENT_INNERHTML_BUGGY = (function(){
      var el = document.createElement("select"),
          isBuggy = true;
      el.innerHTML = "<option value=\"test\">test</option>";
      if (el.options && el.options[0]) {
        isBuggy = el.options[0].nodeName.toUpperCase() !== "OPTION";
      }
      el = null;
      return isBuggy;
    })();

    var TABLE_ELEMENT_INNERHTML_BUGGY = (function(){
      try {
        var el = document.createElement("table");
        if (el && el.tBodies) {
          el.innerHTML = "<tbody><tr><td>test</td></tr></tbody>";
          var isBuggy = typeof el.tBodies[0] == "undefined";
          el = null;
          return isBuggy;
        }
      } catch (e) {
        return true;
      }
    })();

    var SCRIPT_ELEMENT_REJECTS_TEXTNODE_APPENDING = (function () {
      var s = document.createElement("script"),
          isBuggy = false;
      try {
        s.appendChild(document.createTextNode(""));
        isBuggy = !s.firstChild ||
          s.firstChild && s.firstChild.nodeType !== 3;
      } catch (e) {
        isBuggy = true;
      }
      s = null;
      return isBuggy;
    })();

    function update(element, content) {
      element = $(element);

      if (content && content.toElement)
        content = content.toElement();

      if (Object.isElement(content))
        return element.update().insert(content);

      content = Object.toHTML(content);

      var tagName = element.tagName.toUpperCase();

      if (tagName === 'SCRIPT' && SCRIPT_ELEMENT_REJECTS_TEXTNODE_APPENDING) {
        element.text = content;
        return element;
      }

      if (SELECT_ELEMENT_INNERHTML_BUGGY || TABLE_ELEMENT_INNERHTML_BUGGY) {
        if (tagName in Element._insertionTranslations.tags) {
          while (element.firstChild) {
            element.removeChild(element.firstChild);
          }
          Element._getContentFromAnonymousElement(tagName, content.stripScripts())
            .each(function(node) {
              element.appendChild(node)
            });
        }
        else {
          element.innerHTML = content.stripScripts();
        }
      }
      else {
        element.innerHTML = content.stripScripts();
      }

      content.evalScripts.bind(content).defer();
      return element;
    }

    return update;
  })(),

  replace: function(element, content) {
    element = $(element);
    if (content && content.toElement) content = content.toElement();
    else if (!Object.isElement(content)) {
      content = Object.toHTML(content);
      var range = element.ownerDocument.createRange();
      range.selectNode(element);
      content.evalScripts.bind(content).defer();
      content = range.createContextualFragment(content.stripScripts());
    }
    element.parentNode.replaceChild(content, element);
    return element;
  },

  insert: function(element, insertions) {
    element = $(element);

    if (Object.isString(insertions) || Object.isNumber(insertions) ||
        Object.isElement(insertions) || (insertions && (insertions.toElement || insertions.toHTML)))
          insertions = {bottom:insertions};

    var content, insert, tagName, childNodes;

    for (var position in insertions) {
      content  = insertions[position];
      position = position.toLowerCase();
      insert = Element._insertionTranslations[position];

      if (content && content.toElement) content = content.toElement();
      if (Object.isElement(content)) {
        insert(element, content);
        continue;
      }

      content = Object.toHTML(content);

      tagName = ((position == 'before' || position == 'after')
        ? element.parentNode : element).tagName.toUpperCase();

      childNodes = Element._getContentFromAnonymousElement(tagName, content.stripScripts());

      if (position == 'top' || position == 'after') childNodes.reverse();
      childNodes.each(insert.curry(element));

      content.evalScripts.bind(content).defer();
    }

    return element;
  },

  wrap: function(element, wrapper, attributes) {
    element = $(element);
    if (Object.isElement(wrapper))
      $(wrapper).writeAttribute(attributes || { });
    else if (Object.isString(wrapper)) wrapper = new Element(wrapper, attributes);
    else wrapper = new Element('div', wrapper);
    if (element.parentNode)
      element.parentNode.replaceChild(wrapper, element);
    wrapper.appendChild(element);
    return wrapper;
  },

  inspect: function(element) {
    element = $(element);
    var result = '<' + element.tagName.toLowerCase();
    $H({'id': 'id', 'className': 'class'}).each(function(pair) {
      var property = pair.first(), attribute = pair.last();
      var value = (element[property] || '').toString();
      if (value) result += ' ' + attribute + '=' + value.inspect(true);
    });
    return result + '>';
  },

  recursivelyCollect: function(element, property) {
    element = $(element);
    var elements = [];
    while (element = element[property])
      if (element.nodeType == 1)
        elements.push(Element.extend(element));
    return elements;
  },

  ancestors: function(element) {
    return Element.recursivelyCollect(element, 'parentNode');
  },

  descendants: function(element) {
    return Element.select(element, "*");
  },

  firstDescendant: function(element) {
    element = $(element).firstChild;
    while (element && element.nodeType != 1) element = element.nextSibling;
    return $(element);
  },

  immediateDescendants: function(element) {
    if (!(element = $(element).firstChild)) return [];
    while (element && element.nodeType != 1) element = element.nextSibling;
    if (element) return [element].concat($(element).nextSiblings());
    return [];
  },

  previousSiblings: function(element) {
    return Element.recursivelyCollect(element, 'previousSibling');
  },

  nextSiblings: function(element) {
    return Element.recursivelyCollect(element, 'nextSibling');
  },

  siblings: function(element) {
    element = $(element);
    return Element.previousSiblings(element).reverse()
      .concat(Element.nextSiblings(element));
  },

  match: function(element, selector) {
    if (Object.isString(selector))
      selector = new Selector(selector);
    return selector.match($(element));
  },

  up: function(element, expression, index) {
    element = $(element);
    if (arguments.length == 1) return $(element.parentNode);
    var ancestors = Element.ancestors(element);
    return Object.isNumber(expression) ? ancestors[expression] :
      Selector.findElement(ancestors, expression, index);
  },

  down: function(element, expression, index) {
    element = $(element);
    if (arguments.length == 1) return Element.firstDescendant(element);
    return Object.isNumber(expression) ? Element.descendants(element)[expression] :
      Element.select(element, expression)[index || 0];
  },

  previous: function(element, expression, index) {
    element = $(element);
    if (arguments.length == 1) return $(Selector.handlers.previousElementSibling(element));
    var previousSiblings = Element.previousSiblings(element);
    return Object.isNumber(expression) ? previousSiblings[expression] :
      Selector.findElement(previousSiblings, expression, index);
  },

  next: function(element, expression, index) {
    element = $(element);
    if (arguments.length == 1) return $(Selector.handlers.nextElementSibling(element));
    var nextSiblings = Element.nextSiblings(element);
    return Object.isNumber(expression) ? nextSiblings[expression] :
      Selector.findElement(nextSiblings, expression, index);
  },


  select: function(element) {
    var args = Array.prototype.slice.call(arguments, 1);
    return Selector.findChildElements(element, args);
  },

  adjacent: function(element) {
    var args = Array.prototype.slice.call(arguments, 1);
    return Selector.findChildElements(element.parentNode, args).without(element);
  },

  identify: function(element) {
    element = $(element);
    var id = Element.readAttribute(element, 'id');
    if (id) return id;
    do { id = 'anonymous_element_' + Element.idCounter++ } while ($(id));
    Element.writeAttribute(element, 'id', id);
    return id;
  },

  readAttribute: function(element, name) {
    element = $(element);
    if (Prototype.Browser.IE) {
      var t = Element._attributeTranslations.read;
      if (t.values[name]) return t.values[name](element, name);
      if (t.names[name]) name = t.names[name];
      if (name.include(':')) {
        return (!element.attributes || !element.attributes[name]) ? null :
         element.attributes[name].value;
      }
    }
    return element.getAttribute(name);
  },

  writeAttribute: function(element, name, value) {
    element = $(element);
    var attributes = { }, t = Element._attributeTranslations.write;

    if (typeof name == 'object') attributes = name;
    else attributes[name] = Object.isUndefined(value) ? true : value;

    for (var attr in attributes) {
      name = t.names[attr] || attr;
      value = attributes[attr];
      if (t.values[attr]) name = t.values[attr](element, value);
      if (value === false || value === null)
        element.removeAttribute(name);
      else if (value === true)
        element.setAttribute(name, name);
      else element.setAttribute(name, value);
    }
    return element;
  },

  getHeight: function(element) {
    return Element.getDimensions(element).height;
  },

  getWidth: function(element) {
    return Element.getDimensions(element).width;
  },

  classNames: function(element) {
    return new Element.ClassNames(element);
  },

  hasClassName: function(element, className) {
    if (!(element = $(element))) return;
    var elementClassName = element.className;
    return (elementClassName.length > 0 && (elementClassName == className ||
      new RegExp("(^|\\s)" + className + "(\\s|$)").test(elementClassName)));
  },

  addClassName: function(element, className) {
    if (!(element = $(element))) return;
    if (!Element.hasClassName(element, className))
      element.className += (element.className ? ' ' : '') + className;
    return element;
  },

  removeClassName: function(element, className) {
    if (!(element = $(element))) return;
    element.className = element.className.replace(
      new RegExp("(^|\\s+)" + className + "(\\s+|$)"), ' ').strip();
    return element;
  },

  toggleClassName: function(element, className) {
    if (!(element = $(element))) return;
    return Element[Element.hasClassName(element, className) ?
      'removeClassName' : 'addClassName'](element, className);
  },

  cleanWhitespace: function(element) {
    element = $(element);
    var node = element.firstChild;
    while (node) {
      var nextNode = node.nextSibling;
      if (node.nodeType == 3 && !/\S/.test(node.nodeValue))
        element.removeChild(node);
      node = nextNode;
    }
    return element;
  },

  empty: function(element) {
    return $(element).innerHTML.blank();
  },

  descendantOf: function(element, ancestor) {
    element = $(element), ancestor = $(ancestor);

    if (element.compareDocumentPosition)
      return (element.compareDocumentPosition(ancestor) & 8) === 8;

    if (ancestor.contains)
      return ancestor.contains(element) && ancestor !== element;

    while (element = element.parentNode)
      if (element == ancestor) return true;

    return false;
  },

  scrollTo: function(element) {
    element = $(element);
    var pos = Element.cumulativeOffset(element);
    window.scrollTo(pos[0], pos[1]);
    return element;
  },

  getStyle: function(element, style) {
    element = $(element);
    style = style == 'float' ? 'cssFloat' : style.camelize();
    var value = element.style[style];
    if (!value || value == 'auto') {
      var css = document.defaultView.getComputedStyle(element, null);
      value = css ? css[style] : null;
    }
    if (style == 'opacity') return value ? parseFloat(value) : 1.0;
    return value == 'auto' ? null : value;
  },

  getOpacity: function(element) {
    return $(element).getStyle('opacity');
  },

  setStyle: function(element, styles) {
    element = $(element);
    var elementStyle = element.style, match;
    if (Object.isString(styles)) {
      element.style.cssText += ';' + styles;
      return styles.include('opacity') ?
        element.setOpacity(styles.match(/opacity:\s*(\d?\.?\d*)/)[1]) : element;
    }
    for (var property in styles)
      if (property == 'opacity') element.setOpacity(styles[property]);
      else
        elementStyle[(property == 'float' || property == 'cssFloat') ?
          (Object.isUndefined(elementStyle.styleFloat) ? 'cssFloat' : 'styleFloat') :
            property] = styles[property];

    return element;
  },

  setOpacity: function(element, value) {
    element = $(element);
    element.style.opacity = (value == 1 || value === '') ? '' :
      (value < 0.00001) ? 0 : value;
    return element;
  },

  getDimensions: function(element) {
    element = $(element);
    var display = Element.getStyle(element, 'display');
    if (display != 'none' && display != null) // Safari bug
      return {width: element.offsetWidth, height: element.offsetHeight};

    var els = element.style;
    var originalVisibility = els.visibility;
    var originalPosition = els.position;
    var originalDisplay = els.display;
    els.visibility = 'hidden';
    if (originalPosition != 'fixed') // Switching fixed to absolute causes issues in Safari
      els.position = 'absolute';
    els.display = 'block';
    var originalWidth = element.clientWidth;
    var originalHeight = element.clientHeight;
    els.display = originalDisplay;
    els.position = originalPosition;
    els.visibility = originalVisibility;
    return {width: originalWidth, height: originalHeight};
  },

  makePositioned: function(element) {
    element = $(element);
    var pos = Element.getStyle(element, 'position');
    if (pos == 'static' || !pos) {
      element._madePositioned = true;
      element.style.position = 'relative';
      if (Prototype.Browser.Opera) {
        element.style.top = 0;
        element.style.left = 0;
      }
    }
    return element;
  },

  undoPositioned: function(element) {
    element = $(element);
    if (element._madePositioned) {
      element._madePositioned = undefined;
      element.style.position =
        element.style.top =
        element.style.left =
        element.style.bottom =
        element.style.right = '';
    }
    return element;
  },

  makeClipping: function(element) {
    element = $(element);
    if (element._overflow) return element;
    element._overflow = Element.getStyle(element, 'overflow') || 'auto';
    if (element._overflow !== 'hidden')
      element.style.overflow = 'hidden';
    return element;
  },

  undoClipping: function(element) {
    element = $(element);
    if (!element._overflow) return element;
    element.style.overflow = element._overflow == 'auto' ? '' : element._overflow;
    element._overflow = null;
    return element;
  },

  cumulativeOffset: function(element) {
    var valueT = 0, valueL = 0;
    do {
      valueT += element.offsetTop  || 0;
      valueL += element.offsetLeft || 0;
      element = element.offsetParent;
    } while (element);
    return Element._returnOffset(valueL, valueT);
  },

  positionedOffset: function(element) {
    var valueT = 0, valueL = 0;
    do {
      valueT += element.offsetTop  || 0;
      valueL += element.offsetLeft || 0;
      element = element.offsetParent;
      if (element) {
        if (element.tagName.toUpperCase() == 'BODY') break;
        var p = Element.getStyle(element, 'position');
        if (p !== 'static') break;
      }
    } while (element);
    return Element._returnOffset(valueL, valueT);
  },

  absolutize: function(element) {
    element = $(element);
    if (Element.getStyle(element, 'position') == 'absolute') return element;

    var offsets = Element.positionedOffset(element);
    var top     = offsets[1];
    var left    = offsets[0];
    var width   = element.clientWidth;
    var height  = element.clientHeight;

    element._originalLeft   = left - parseFloat(element.style.left  || 0);
    element._originalTop    = top  - parseFloat(element.style.top || 0);
    element._originalWidth  = element.style.width;
    element._originalHeight = element.style.height;

    element.style.position = 'absolute';
    element.style.top    = top + 'px';
    element.style.left   = left + 'px';
    element.style.width  = width + 'px';
    element.style.height = height + 'px';
    return element;
  },

  relativize: function(element) {
    element = $(element);
    if (Element.getStyle(element, 'position') == 'relative') return element;

    element.style.position = 'relative';
    var top  = parseFloat(element.style.top  || 0) - (element._originalTop || 0);
    var left = parseFloat(element.style.left || 0) - (element._originalLeft || 0);

    element.style.top    = top + 'px';
    element.style.left   = left + 'px';
    element.style.height = element._originalHeight;
    element.style.width  = element._originalWidth;
    return element;
  },

  cumulativeScrollOffset: function(element) {
    var valueT = 0, valueL = 0;
    do {
      valueT += element.scrollTop  || 0;
      valueL += element.scrollLeft || 0;
      element = element.parentNode;
    } while (element);
    return Element._returnOffset(valueL, valueT);
  },

  getOffsetParent: function(element) {
    if (element.offsetParent) return $(element.offsetParent);
    if (element == document.body) return $(element);
      if (element.tagName == 'HTML') return $(element); // mhollauf mindmeister fix, see http://dev.rubyonrails.org/ticket/11182

    while ((element = element.parentNode) && element != document.body)
      if (Element.getStyle(element, 'position') != 'static')
        return $(element);

    return $(document.body);
  },

  viewportOffset: function(forElement) {
    var valueT = 0, valueL = 0;

    var element = forElement;
    do {
      valueT += element.offsetTop  || 0;
      valueL += element.offsetLeft || 0;

      if (element.offsetParent == document.body &&
        Element.getStyle(element, 'position') == 'absolute') break;

    } while (element = element.offsetParent);

    element = forElement;
    do {
      if (!Prototype.Browser.Opera || (element.tagName && (element.tagName.toUpperCase() == 'BODY'))) {
        valueT -= element.scrollTop  || 0;
        valueL -= element.scrollLeft || 0;
      }
    } while (element = element.parentNode);

    return Element._returnOffset(valueL, valueT);
  },

  clonePosition: function(element, source) {
    var options = Object.extend({
      setLeft:    true,
      setTop:     true,
      setWidth:   true,
      setHeight:  true,
      offsetTop:  0,
      offsetLeft: 0
    }, arguments[2] || { });

    source = $(source);
    var p = Element.viewportOffset(source);

    element = $(element);
    var delta = [0, 0];
    var parent = null;
    if (Element.getStyle(element, 'position') == 'absolute') {
      parent = Element.getOffsetParent(element);
      delta = Element.viewportOffset(parent);
    }

    if (parent == document.body) {
      delta[0] -= document.body.offsetLeft;
      delta[1] -= document.body.offsetTop;
    }

    if (options.setLeft)   element.style.left  = (p[0] - delta[0] + options.offsetLeft) + 'px';
    if (options.setTop)    element.style.top   = (p[1] - delta[1] + options.offsetTop) + 'px';
    if (options.setWidth)  element.style.width = source.offsetWidth + 'px';
    if (options.setHeight) element.style.height = source.offsetHeight + 'px';
    return element;
  }
};

Object.extend(Element.Methods, {
  getElementsBySelector: Element.Methods.select,

  childElements: Element.Methods.immediateDescendants
});

Element._attributeTranslations = {
  write: {
    names: {
      className: 'class',
      htmlFor:   'for'
    },
    values: { }
  }
};

if (Prototype.Browser.Opera) {
  Element.Methods.getStyle = Element.Methods.getStyle.wrap(
    function(proceed, element, style) {
      switch (style) {
        case 'left': case 'top': case 'right': case 'bottom':
          if (proceed(element, 'position') === 'static') return null;
        case 'height': case 'width':
          if (!Element.visible(element)) return null;

          var dim = parseInt(proceed(element, style), 10);

          if (dim !== element['offset' + style.capitalize()])
            return dim + 'px';

          var properties;
          if (style === 'height') {
            properties = ['border-top-width', 'padding-top',
             'padding-bottom', 'border-bottom-width'];
          }
          else {
            properties = ['border-left-width', 'padding-left',
             'padding-right', 'border-right-width'];
          }
          return properties.inject(dim, function(memo, property) {
            var val = proceed(element, property);
            return val === null ? memo : memo - parseInt(val, 10);
          }) + 'px';
        default: return proceed(element, style);
      }
    }
  );

  Element.Methods.readAttribute = Element.Methods.readAttribute.wrap(
    function(proceed, element, attribute) {
      if (attribute === 'title') return element.title;
      return proceed(element, attribute);
    }
  );
}

else if (Prototype.Browser.IE) {
  Element.Methods.getOffsetParent = Element.Methods.getOffsetParent.wrap(
    function(proceed, element) {
      element = $(element);
      try { element.offsetParent }
      catch(e) { return $(document.body) }
      var position = element.getStyle('position');
      if (position !== 'static') return proceed(element);
      element.setStyle({ position: 'relative' });
      var value = proceed(element);
      element.setStyle({ position: position });
      return value;
    }
  );

  $w('positionedOffset viewportOffset').each(function(method) {
    Element.Methods[method] = Element.Methods[method].wrap(
      function(proceed, element) {
        element = $(element);
        try { element.offsetParent }
        catch(e) { return Element._returnOffset(0,0) }
        var position = element.getStyle('position');
        if (position !== 'static') return proceed(element);
        var offsetParent = element.getOffsetParent();
        if (offsetParent && offsetParent.getStyle('position') === 'fixed')
          offsetParent.setStyle({ zoom: 1 });
        element.setStyle({ position: 'relative' });
        var value = proceed(element);
        element.setStyle({ position: position });
        return value;
      }
    );
  });

  Element.Methods.cumulativeOffset = Element.Methods.cumulativeOffset.wrap(
    function(proceed, element) {
      try { element.offsetParent }
      catch(e) { return Element._returnOffset(0,0) }
      return proceed(element);
    }
  );

  Element.Methods.getStyle = function(element, style) {
    element = $(element);
    style = (style == 'float' || style == 'cssFloat') ? 'styleFloat' : style.camelize();
    var value = element.style[style];
    if (!value && element.currentStyle) value = element.currentStyle[style];

    if (style == 'opacity') {
      if (value = (element.getStyle('filter') || '').match(/alpha\(opacity=(.*)\)/))
        if (value[1]) return parseFloat(value[1]) / 100;
      return 1.0;
    }

    if (value == 'auto') {
      if ((style == 'width' || style == 'height') && (element.getStyle('display') != 'none'))
        return element['offset' + style.capitalize()] + 'px';
      return null;
    }
    return value;
  };

  Element.Methods.setOpacity = function(element, value) {
    function stripAlpha(filter){
      return filter.replace(/alpha\([^\)]*\)/gi,'');
    }
    element = $(element);
    var currentStyle = element.currentStyle;
    if ((currentStyle && !currentStyle.hasLayout) ||
      (!currentStyle && element.style.zoom == 'normal'))
        element.style.zoom = 1;

    var filter = element.getStyle('filter'), style = element.style;
    if (value == 1 || value === '') {
      (filter = stripAlpha(filter)) ?
        style.filter = filter : style.removeAttribute('filter');
      return element;
    } else if (value < 0.00001) value = 0;
    style.filter = stripAlpha(filter) +
      'alpha(opacity=' + (value * 100) + ')';
    return element;
  };

  Element._attributeTranslations = (function(){

    var classProp = 'className';
    var forProp = 'for';

    var el = document.createElement('div');

    el.setAttribute(classProp, 'x');

    if (el.className !== 'x') {
      el.setAttribute('class', 'x');
      if (el.className === 'x') {
        classProp = 'class';
      }
    }
    el = null;

    el = document.createElement('label');
    el.setAttribute(forProp, 'x');
    if (el.htmlFor !== 'x') {
      el.setAttribute('htmlFor', 'x');
      if (el.htmlFor === 'x') {
        forProp = 'htmlFor';
      }
    }
    el = null;

    return {
      read: {
        names: {
          'class':      classProp,
          'className':  classProp,
          'for':        forProp,
          'htmlFor':    forProp
        },
        values: {
          _getAttr: function(element, attribute) {
            return element.getAttribute(attribute);
          },
          _getAttr2: function(element, attribute) {
            return element.getAttribute(attribute, 2);
          },
          _getAttrNode: function(element, attribute) {
            var node = element.getAttributeNode(attribute);
            return node ? node.value : "";
          },
          _getEv: (function(){

            var el = document.createElement('div');
            el.onclick = Prototype.emptyFunction;
            var value = el.getAttribute('onclick');
            var f;

            if (String(value).indexOf('{') > -1) {
              f = function(element, attribute) {
                attribute = element.getAttribute(attribute);
                if (!attribute) return null;
                attribute = attribute.toString();
                attribute = attribute.split('{')[1];
                attribute = attribute.split('}')[0];
                return attribute.strip();
              };
            }
            else if (value === '') {
              f = function(element, attribute) {
                attribute = element.getAttribute(attribute);
                if (!attribute) return null;
                return attribute.strip();
              };
            }
            el = null;
            return f;
          })(),
          _flag: function(element, attribute) {
            return $(element).hasAttribute(attribute) ? attribute : null;
          },
          style: function(element) {
            return element.style.cssText.toLowerCase();
          },
          title: function(element) {
            return element.title;
          }
        }
      }
    }
  })();

  Element._attributeTranslations.write = {
    names: Object.extend({
      cellpadding: 'cellPadding',
      cellspacing: 'cellSpacing'
    }, Element._attributeTranslations.read.names),
    values: {
      checked: function(element, value) {
        element.checked = !!value;
      },

      style: function(element, value) {
        element.style.cssText = value ? value : '';
      }
    }
  };

  Element._attributeTranslations.has = {};

  $w('colSpan rowSpan vAlign dateTime accessKey tabIndex ' +
      'encType maxLength readOnly longDesc frameBorder').each(function(attr) {
    Element._attributeTranslations.write.names[attr.toLowerCase()] = attr;
    Element._attributeTranslations.has[attr.toLowerCase()] = attr;
  });

  (function(v) {
    Object.extend(v, {
      href:        v._getAttr2,
      src:         v._getAttr2,
      type:        v._getAttr,
      action:      v._getAttrNode,
      disabled:    v._flag,
      checked:     v._flag,
      readonly:    v._flag,
      multiple:    v._flag,
      onload:      v._getEv,
      onunload:    v._getEv,
      onclick:     v._getEv,
      ondblclick:  v._getEv,
      onmousedown: v._getEv,
      onmouseup:   v._getEv,
      onmouseover: v._getEv,
      onmousemove: v._getEv,
      onmouseout:  v._getEv,
      onfocus:     v._getEv,
      onblur:      v._getEv,
      onkeypress:  v._getEv,
      onkeydown:   v._getEv,
      onkeyup:     v._getEv,
      onsubmit:    v._getEv,
      onreset:     v._getEv,
      onselect:    v._getEv,
      onchange:    v._getEv
    });
  })(Element._attributeTranslations.read.values);

  if (Prototype.BrowserFeatures.ElementExtensions) {
    (function() {
      function _descendants(element) {
        var nodes = element.getElementsByTagName('*'), results = [];
        for (var i = 0, node; node = nodes[i]; i++)
          if (node.tagName !== "!") // Filter out comment nodes.
            results.push(node);
        return results;
      }

      Element.Methods.down = function(element, expression, index) {
        element = $(element);
        if (arguments.length == 1) return element.firstDescendant();
        return Object.isNumber(expression) ? _descendants(element)[expression] :
          Element.select(element, expression)[index || 0];
      }
    })();
  }

}

else if (Prototype.Browser.Gecko && /rv:1\.8\.0/.test(navigator.userAgent)) {
  Element.Methods.setOpacity = function(element, value) {
    element = $(element);
    element.style.opacity = (value == 1) ? 0.999999 :
      (value === '') ? '' : (value < 0.00001) ? 0 : value;
    return element;
  };
}

else if (Prototype.Browser.WebKit) {
  Element.Methods.setOpacity = function(element, value) {
    element = $(element);
    element.style.opacity = (value == 1 || value === '') ? '' :
      (value < 0.00001) ? 0 : value;

    if (value == 1)
      if(element.tagName.toUpperCase() == 'IMG' && element.width) {
        element.width++; element.width--;
      } else try {
        var n = document.createTextNode(' ');
        element.appendChild(n);
        element.removeChild(n);
      } catch (e) { }

    return element;
  };

  Element.Methods.cumulativeOffset = function(element) {
    var valueT = 0, valueL = 0;
    do {
      valueT += element.offsetTop  || 0;
      valueL += element.offsetLeft || 0;
      if (element.offsetParent == document.body)
        if (Element.getStyle(element, 'position') == 'absolute') break;

      element = element.offsetParent;
    } while (element);

    return Element._returnOffset(valueL, valueT);
  };
}

if ('outerHTML' in document.documentElement) {
  Element.Methods.replace = function(element, content) {
    element = $(element);

    if (content && content.toElement) content = content.toElement();
    if (Object.isElement(content)) {
      element.parentNode.replaceChild(content, element);
      return element;
    }

    content = Object.toHTML(content);
    var parent = element.parentNode, tagName = parent.tagName.toUpperCase();

    if (Element._insertionTranslations.tags[tagName]) {
      var nextSibling = element.next();
      var fragments = Element._getContentFromAnonymousElement(tagName, content.stripScripts());
      parent.removeChild(element);
      if (nextSibling)
        fragments.each(function(node) { parent.insertBefore(node, nextSibling) });
      else
        fragments.each(function(node) { parent.appendChild(node) });
    }
    else element.outerHTML = content.stripScripts();

    content.evalScripts.bind(content).defer();
    return element;
  };
}

Element._returnOffset = function(l, t) {
  var result = [l, t];
  result.left = l;
  result.top = t;
  return result;
};

Element._getContentFromAnonymousElement = function(tagName, html) {
  var div = new Element('div'), t = Element._insertionTranslations.tags[tagName];
  if (t) {
    div.innerHTML = t[0] + html + t[1];
    t[2].times(function() { div = div.firstChild });
  } else div.innerHTML = html;
  return $A(div.childNodes);
};

Element._insertionTranslations = {
  before: function(element, node) {
    element.parentNode.insertBefore(node, element);
  },
  top: function(element, node) {
    element.insertBefore(node, element.firstChild);
  },
  bottom: function(element, node) {
    element.appendChild(node);
  },
  after: function(element, node) {
    element.parentNode.insertBefore(node, element.nextSibling);
  },
  tags: {
    TABLE:  ['<table>',                '</table>',                   1],
    TBODY:  ['<table><tbody>',         '</tbody></table>',           2],
    TR:     ['<table><tbody><tr>',     '</tr></tbody></table>',      3],
    TD:     ['<table><tbody><tr><td>', '</td></tr></tbody></table>', 4],
    SELECT: ['<select>',               '</select>',                  1]
  }
};

(function() {
  var tags = Element._insertionTranslations.tags;
  Object.extend(tags, {
    THEAD: tags.TBODY,
    TFOOT: tags.TBODY,
    TH:    tags.TD
  });
})();

Element.Methods.Simulated = {
  hasAttribute: function(element, attribute) {
    attribute = Element._attributeTranslations.has[attribute] || attribute;
    var node = $(element).getAttributeNode(attribute);
    return !!(node && node.specified);
  }
};

Element.Methods.ByTag = { };

Object.extend(Element, Element.Methods);

(function(div) {

  if (!Prototype.BrowserFeatures.ElementExtensions && div['__proto__']) {
    window.HTMLElement = { };
    window.HTMLElement.prototype = div['__proto__'];
    Prototype.BrowserFeatures.ElementExtensions = true;
  }

  div = null;

})(document.createElement('div'))

Element.extend = (function() {

  function checkDeficiency(tagName) {
    if (typeof window.Element != 'undefined') {
      var proto = window.Element.prototype;
      if (proto) {
        var id = '_' + (Math.random()+'').slice(2);
        var el = document.createElement(tagName);
        proto[id] = 'x';
        var isBuggy = (el[id] !== 'x');
        delete proto[id];
        el = null;
        return isBuggy;
      }
    }
    return false;
  }

  function extendElementWith(element, methods) {
    for (var property in methods) {
      var value = methods[property];
      if (Object.isFunction(value) && !(property in element))
        element[property] = value.methodize();
    }
  }

  var HTMLOBJECTELEMENT_PROTOTYPE_BUGGY = checkDeficiency('object');

  if (Prototype.BrowserFeatures.SpecificElementExtensions) {
    if (HTMLOBJECTELEMENT_PROTOTYPE_BUGGY) {
      return function(element) {
        if (element && typeof element._extendedByPrototype == 'undefined') {
          var t = element.tagName;
          if (t && (/^(?:object|applet|embed)$/i.test(t))) {
            extendElementWith(element, Element.Methods);
            extendElementWith(element, Element.Methods.Simulated);
            extendElementWith(element, Element.Methods.ByTag[t.toUpperCase()]);
          }
        }
        return element;
      }
    }
    return Prototype.K;
  }

  var Methods = { }, ByTag = Element.Methods.ByTag;

  var extend = Object.extend(function(element) {
    if (!element || typeof element._extendedByPrototype != 'undefined' ||
        element.nodeType != 1 || element == window) return element;

    var methods = Object.clone(Methods),
        tagName = element.tagName.toUpperCase();

    if (ByTag[tagName]) Object.extend(methods, ByTag[tagName]);

    extendElementWith(element, methods);

    element._extendedByPrototype = Prototype.emptyFunction;
    return element;

  }, {
    refresh: function() {
      if (!Prototype.BrowserFeatures.ElementExtensions) {
        Object.extend(Methods, Element.Methods);
        Object.extend(Methods, Element.Methods.Simulated);
      }
    }
  });

  extend.refresh();
  return extend;
})();

Element.hasAttribute = function(element, attribute) {
  if (element.hasAttribute) return element.hasAttribute(attribute);
  return Element.Methods.Simulated.hasAttribute(element, attribute);
};

Element.addMethods = function(methods) {
  var F = Prototype.BrowserFeatures, T = Element.Methods.ByTag;

  if (!methods) {
    Object.extend(Form, Form.Methods);
    Object.extend(Form.Element, Form.Element.Methods);
    Object.extend(Element.Methods.ByTag, {
      "FORM":     Object.clone(Form.Methods),
      "INPUT":    Object.clone(Form.Element.Methods),
      "SELECT":   Object.clone(Form.Element.Methods),
      "TEXTAREA": Object.clone(Form.Element.Methods)
    });
  }

  if (arguments.length == 2) {
    var tagName = methods;
    methods = arguments[1];
  }

  if (!tagName) Object.extend(Element.Methods, methods || { });
  else {
    if (Object.isArray(tagName)) tagName.each(extend);
    else extend(tagName);
  }

  function extend(tagName) {
    tagName = tagName.toUpperCase();
    if (!Element.Methods.ByTag[tagName])
      Element.Methods.ByTag[tagName] = { };
    Object.extend(Element.Methods.ByTag[tagName], methods);
  }

  function copy(methods, destination, onlyIfAbsent) {
    onlyIfAbsent = onlyIfAbsent || false;
    for (var property in methods) {
      var value = methods[property];
      if (!Object.isFunction(value)) continue;
      if (!onlyIfAbsent || !(property in destination))
        destination[property] = value.methodize();
    }
  }

  function findDOMClass(tagName) {
    var klass;
    var trans = {
      "OPTGROUP": "OptGroup", "TEXTAREA": "TextArea", "P": "Paragraph",
      "FIELDSET": "FieldSet", "UL": "UList", "OL": "OList", "DL": "DList",
      "DIR": "Directory", "H1": "Heading", "H2": "Heading", "H3": "Heading",
      "H4": "Heading", "H5": "Heading", "H6": "Heading", "Q": "Quote",
      "INS": "Mod", "DEL": "Mod", "A": "Anchor", "IMG": "Image", "CAPTION":
      "TableCaption", "COL": "TableCol", "COLGROUP": "TableCol", "THEAD":
      "TableSection", "TFOOT": "TableSection", "TBODY": "TableSection", "TR":
      "TableRow", "TH": "TableCell", "TD": "TableCell", "FRAMESET":
      "FrameSet", "IFRAME": "IFrame"
    };
    if (trans[tagName]) klass = 'HTML' + trans[tagName] + 'Element';
    if (window[klass]) return window[klass];
    klass = 'HTML' + tagName + 'Element';
    if (window[klass]) return window[klass];
    klass = 'HTML' + tagName.capitalize() + 'Element';
    if (window[klass]) return window[klass];

    var element = document.createElement(tagName);
    var proto = element['__proto__'] || element.constructor.prototype;
    element = null;
    return proto;
  }

  var elementPrototype = window.HTMLElement ? HTMLElement.prototype :
   Element.prototype;

  if (F.ElementExtensions) {
    copy(Element.Methods, elementPrototype);
    copy(Element.Methods.Simulated, elementPrototype, true);
  }

  if (F.SpecificElementExtensions) {
    for (var tag in Element.Methods.ByTag) {
      var klass = findDOMClass(tag);
      if (Object.isUndefined(klass)) continue;
      copy(T[tag], klass.prototype);
    }
  }

  Object.extend(Element, Element.Methods);
  delete Element.ByTag;

  if (Element.extend.refresh) Element.extend.refresh();
  Element.cache = { };
};


document.viewport = {

  getDimensions: function() {
    return { width: this.getWidth(), height: this.getHeight() };
  },

  getScrollOffsets: function() {
    return Element._returnOffset(
      window.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft,
      window.pageYOffset || document.documentElement.scrollTop  || document.body.scrollTop);
  }
};

(function(viewport) {
  var B = Prototype.Browser, doc = document, element, property = {};

  function getRootElement() {
    if (B.WebKit && !doc.evaluate)
      return document;

    if (B.Opera && window.parseFloat(window.opera.version()) < 9.5)
      return document.body;

    return document.documentElement;
  }

  function define(D) {
    if (!element) element = getRootElement();

    property[D] = 'client' + D;

    viewport['get' + D] = function() { return element[property[D]] };
    return viewport['get' + D]();
  }

  viewport.getWidth  = define.curry('Width');

  viewport.getHeight = define.curry('Height');
})(document.viewport);


Element.Storage = {
  UID: 1
};

Element.addMethods({
  getStorage: function(element) {
    if (!(element = $(element))) return;

    var uid;
    if (element === window) {
      uid = 0;
    } else {
      if (typeof element._prototypeUID === "undefined")
        element._prototypeUID = [Element.Storage.UID++];
      uid = element._prototypeUID[0];
    }

    if (!Element.Storage[uid])
      Element.Storage[uid] = $H();

    return Element.Storage[uid];
  },

  store: function(element, key, value) {
    if (!(element = $(element))) return;

    if (arguments.length === 2) {
      Element.getStorage(element).update(key);
    } else {
      Element.getStorage(element).set(key, value);
    }

    return element;
  },

  retrieve: function(element, key, defaultValue) {
    if (!(element = $(element))) return;
    var hash = Element.getStorage(element), value = hash.get(key);

    if (Object.isUndefined(value)) {
      hash.set(key, defaultValue);
      value = defaultValue;
    }

    return value;
  },

  clone: function(element, deep) {
    if (!(element = $(element))) return;
    var clone = element.cloneNode(deep);
    clone._prototypeUID = void 0;
    if (deep) {
      var descendants = Element.select(clone, '*'),
          i = descendants.length;
      while (i--) {
        descendants[i]._prototypeUID = void 0;
      }
    }
    return Element.extend(clone);
  }
});
/* Portions of the Selector class are derived from Jack Slocum's DomQuery,
 * part of YUI-Ext version 0.40, distributed under the terms of an MIT-style
 * license.  Please see http://www.yui-ext.com/ for more information. */

var Selector = Class.create({
  initialize: function(expression) {
    this.expression = expression.strip();

    if (this.shouldUseSelectorsAPI()) {
      this.mode = 'selectorsAPI';
    } else if (this.shouldUseXPath()) {
      this.mode = 'xpath';
      this.compileXPathMatcher();
    } else {
      this.mode = "normal";
      this.compileMatcher();
    }

  },

  shouldUseXPath: (function() {

    var IS_DESCENDANT_SELECTOR_BUGGY = (function(){
      var isBuggy = false;
      if (document.evaluate && window.XPathResult) {
        var el = document.createElement('div');
        el.innerHTML = '<ul><li></li></ul><div><ul><li></li></ul></div>';

        var xpath = ".//*[local-name()='ul' or local-name()='UL']" +
          "//*[local-name()='li' or local-name()='LI']";

        var result = document.evaluate(xpath, el, null,
          XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null);

        isBuggy = (result.snapshotLength !== 2);
        el = null;
      }
      return isBuggy;
    })();

    return function() {
      if (!Prototype.BrowserFeatures.XPath) return false;

      var e = this.expression;

      if (Prototype.Browser.WebKit &&
       (e.include("-of-type") || e.include(":empty")))
        return false;

      if ((/(\[[\w-]*?:|:checked)/).test(e))
        return false;

      if (IS_DESCENDANT_SELECTOR_BUGGY) return false;

      return true;
    }

  })(),

  shouldUseSelectorsAPI: function() {
    if (!Prototype.BrowserFeatures.SelectorsAPI) return false;

    if (Selector.CASE_INSENSITIVE_CLASS_NAMES) return false;

    if (!Selector._div) Selector._div = new Element('div');

    try {
      Selector._div.querySelector(this.expression);
    } catch(e) {
      return false;
    }

    return true;
  },

  compileMatcher: function() {
    var e = this.expression, ps = Selector.patterns, h = Selector.handlers,
        c = Selector.criteria, le, p, m, len = ps.length, name;

    if (Selector._cache[e]) {
      this.matcher = Selector._cache[e];
      return;
    }

    this.matcher = ["this.matcher = function(root) {",
                    "var r = root, h = Selector.handlers, c = false, n;"];

    while (e && le != e && (/\S/).test(e)) {
      le = e;
      for (var i = 0; i<len; i++) {
        p = ps[i].re;
        name = ps[i].name;
        if (m = e.match(p)) {
          this.matcher.push(Object.isFunction(c[name]) ? c[name](m) :
            new Template(c[name]).evaluate(m));
          e = e.replace(m[0], '');
          break;
        }
      }
    }

    this.matcher.push("return h.unique(n);\n}");
    eval(this.matcher.join('\n'));
    Selector._cache[this.expression] = this.matcher;
  },

  compileXPathMatcher: function() {
    var e = this.expression, ps = Selector.patterns,
        x = Selector.xpath, le, m, len = ps.length, name;

    if (Selector._cache[e]) {
      this.xpath = Selector._cache[e]; return;
    }

    this.matcher = ['.//*'];
    while (e && le != e && (/\S/).test(e)) {
      le = e;
      for (var i = 0; i<len; i++) {
        name = ps[i].name;
        if (m = e.match(ps[i].re)) {
          this.matcher.push(Object.isFunction(x[name]) ? x[name](m) :
            new Template(x[name]).evaluate(m));
          e = e.replace(m[0], '');
          break;
        }
      }
    }

    this.xpath = this.matcher.join('');
    Selector._cache[this.expression] = this.xpath;
  },

  findElements: function(root) {
    root = root || document;
    var e = this.expression, results;

    switch (this.mode) {
      case 'selectorsAPI':
        if (root !== document) {
          var oldId = root.id, id = $(root).identify();
          id = id.replace(/([\.:])/g, "\\$1");
          e = "#" + id + " " + e;
        }

        results = $A(root.querySelectorAll(e)).map(Element.extend);
        root.id = oldId;

        return results;
      case 'xpath':
        return document._getElementsByXPath(this.xpath, root);
      default:
       return this.matcher(root);
    }
  },

  match: function(element) {
    this.tokens = [];

    var e = this.expression, ps = Selector.patterns, as = Selector.assertions;
    var le, p, m, len = ps.length, name;

    while (e && le !== e && (/\S/).test(e)) {
      le = e;
      for (var i = 0; i<len; i++) {
        p = ps[i].re;
        name = ps[i].name;
        if (m = e.match(p)) {
          if (as[name]) {
            this.tokens.push([name, Object.clone(m)]);
            e = e.replace(m[0], '');
          } else {
            return this.findElements(document).include(element);
          }
        }
      }
    }

    var match = true, name, matches;
    for (var i = 0, token; token = this.tokens[i]; i++) {
      name = token[0], matches = token[1];
      if (!Selector.assertions[name](element, matches)) {
        match = false; break;
      }
    }

    return match;
  },

  toString: function() {
    return this.expression;
  },

  inspect: function() {
    return "#<Selector:" + this.expression.inspect() + ">";
  }
});

if (Prototype.BrowserFeatures.SelectorsAPI &&
 document.compatMode === 'BackCompat') {
  Selector.CASE_INSENSITIVE_CLASS_NAMES = (function(){
    var div = document.createElement('div'),
     span = document.createElement('span');

    div.id = "prototype_test_id";
    span.className = 'Test';
    div.appendChild(span);
    var isIgnored = (div.querySelector('#prototype_test_id .test') !== null);
    div = span = null;
    return isIgnored;
  })();
}

Object.extend(Selector, {
  _cache: { },

  xpath: {
    descendant:   "//*",
    child:        "/*",
    adjacent:     "/following-sibling::*[1]",
    laterSibling: '/following-sibling::*',
    tagName:      function(m) {
      if (m[1] == '*') return '';
      return "[local-name()='" + m[1].toLowerCase() +
             "' or local-name()='" + m[1].toUpperCase() + "']";
    },
    className:    "[contains(concat(' ', @class, ' '), ' #{1} ')]",
    id:           "[@id='#{1}']",
    attrPresence: function(m) {
      m[1] = m[1].toLowerCase();
      return new Template("[@#{1}]").evaluate(m);
    },
    attr: function(m) {
      m[1] = m[1].toLowerCase();
      m[3] = m[5] || m[6];
      return new Template(Selector.xpath.operators[m[2]]).evaluate(m);
    },
    pseudo: function(m) {
      var h = Selector.xpath.pseudos[m[1]];
      if (!h) return '';
      if (Object.isFunction(h)) return h(m);
      return new Template(Selector.xpath.pseudos[m[1]]).evaluate(m);
    },
    operators: {
      '=':  "[@#{1}='#{3}']",
      '!=': "[@#{1}!='#{3}']",
      '^=': "[starts-with(@#{1}, '#{3}')]",
      '$=': "[substring(@#{1}, (string-length(@#{1}) - string-length('#{3}') + 1))='#{3}']",
      '*=': "[contains(@#{1}, '#{3}')]",
      '~=': "[contains(concat(' ', @#{1}, ' '), ' #{3} ')]",
      '|=': "[contains(concat('-', @#{1}, '-'), '-#{3}-')]"
    },
    pseudos: {
      'first-child': '[not(preceding-sibling::*)]',
      'last-child':  '[not(following-sibling::*)]',
      'only-child':  '[not(preceding-sibling::* or following-sibling::*)]',
      'empty':       "[count(*) = 0 and (count(text()) = 0)]",
      'checked':     "[@checked]",
      'disabled':    "[(@disabled) and (@type!='hidden')]",
      'enabled':     "[not(@disabled) and (@type!='hidden')]",
      'not': function(m) {
        var e = m[6], p = Selector.patterns,
            x = Selector.xpath, le, v, len = p.length, name;

        var exclusion = [];
        while (e && le != e && (/\S/).test(e)) {
          le = e;
          for (var i = 0; i<len; i++) {
            name = p[i].name
            if (m = e.match(p[i].re)) {
              v = Object.isFunction(x[name]) ? x[name](m) : new Template(x[name]).evaluate(m);
              exclusion.push("(" + v.substring(1, v.length - 1) + ")");
              e = e.replace(m[0], '');
              break;
            }
          }
        }
        return "[not(" + exclusion.join(" and ") + ")]";
      },
      'nth-child':      function(m) {
        return Selector.xpath.pseudos.nth("(count(./preceding-sibling::*) + 1) ", m);
      },
      'nth-last-child': function(m) {
        return Selector.xpath.pseudos.nth("(count(./following-sibling::*) + 1) ", m);
      },
      'nth-of-type':    function(m) {
        return Selector.xpath.pseudos.nth("position() ", m);
      },
      'nth-last-of-type': function(m) {
        return Selector.xpath.pseudos.nth("(last() + 1 - position()) ", m);
      },
      'first-of-type':  function(m) {
        m[6] = "1"; return Selector.xpath.pseudos['nth-of-type'](m);
      },
      'last-of-type':   function(m) {
        m[6] = "1"; return Selector.xpath.pseudos['nth-last-of-type'](m);
      },
      'only-of-type':   function(m) {
        var p = Selector.xpath.pseudos; return p['first-of-type'](m) + p['last-of-type'](m);
      },
      nth: function(fragment, m) {
        var mm, formula = m[6], predicate;
        if (formula == 'even') formula = '2n+0';
        if (formula == 'odd')  formula = '2n+1';
        if (mm = formula.match(/^(\d+)$/)) // digit only
          return '[' + fragment + "= " + mm[1] + ']';
        if (mm = formula.match(/^(-?\d*)?n(([+-])(\d+))?/)) { // an+b
          if (mm[1] == "-") mm[1] = -1;
          var a = mm[1] ? Number(mm[1]) : 1;
          var b = mm[2] ? Number(mm[2]) : 0;
          predicate = "[((#{fragment} - #{b}) mod #{a} = 0) and " +
          "((#{fragment} - #{b}) div #{a} >= 0)]";
          return new Template(predicate).evaluate({
            fragment: fragment, a: a, b: b });
        }
      }
    }
  },

  criteria: {
    tagName:      'n = h.tagName(n, r, "#{1}", c);      c = false;',
    className:    'n = h.className(n, r, "#{1}", c);    c = false;',
    id:           'n = h.id(n, r, "#{1}", c);           c = false;',
    attrPresence: 'n = h.attrPresence(n, r, "#{1}", c); c = false;',
    attr: function(m) {
      m[3] = (m[5] || m[6]);
      return new Template('n = h.attr(n, r, "#{1}", "#{3}", "#{2}", c); c = false;').evaluate(m);
    },
    pseudo: function(m) {
      if (m[6]) m[6] = m[6].replace(/"/g, '\\"');
      return new Template('n = h.pseudo(n, "#{1}", "#{6}", r, c); c = false;').evaluate(m);
    },
    descendant:   'c = "descendant";',
    child:        'c = "child";',
    adjacent:     'c = "adjacent";',
    laterSibling: 'c = "laterSibling";'
  },

  patterns: [
    { name: 'laterSibling', re: /^\s*~\s*/ },
    { name: 'child',        re: /^\s*>\s*/ },
    { name: 'adjacent',     re: /^\s*\+\s*/ },
    { name: 'descendant',   re: /^\s/ },

    { name: 'tagName',      re: /^\s*(\*|[\w\-]+)(\b|$)?/ },
    { name: 'id',           re: /^#([\w\-\*]+)(\b|$)/ },
    { name: 'className',    re: /^\.([\w\-\*]+)(\b|$)/ },
    { name: 'pseudo',       re: /^:((first|last|nth|nth-last|only)(-child|-of-type)|empty|checked|(en|dis)abled|not)(\((.*?)\))?(\b|$|(?=\s|[:+~>]))/ },
    { name: 'attrPresence', re: /^\[((?:[\w-]+:)?[\w-]+)\]/ },
    { name: 'attr',         re: /\[((?:[\w-]*:)?[\w-]+)\s*(?:([!^$*~|]?=)\s*((['"])([^\4]*?)\4|([^'"][^\]]*?)))?\]/ }
  ],

  assertions: {
    tagName: function(element, matches) {
      return matches[1].toUpperCase() == element.tagName.toUpperCase();
    },

    className: function(element, matches) {
      return Element.hasClassName(element, matches[1]);
    },

    id: function(element, matches) {
      return element.id === matches[1];
    },

    attrPresence: function(element, matches) {
      return Element.hasAttribute(element, matches[1]);
    },

    attr: function(element, matches) {
      var nodeValue = Element.readAttribute(element, matches[1]);
      return nodeValue && Selector.operators[matches[2]](nodeValue, matches[5] || matches[6]);
    }
  },

  handlers: {
    concat: function(a, b) {
      for (var i = 0, node; node = b[i]; i++)
        a.push(node);
      return a;
    },

    mark: function(nodes) {
      var _true = Prototype.emptyFunction;
      for (var i = 0, node; node = nodes[i]; i++)
        node._countedByPrototype = _true;
      return nodes;
    },

    unmark: (function(){

      var PROPERTIES_ATTRIBUTES_MAP = (function(){
        var el = document.createElement('div'),
            isBuggy = false,
            propName = '_countedByPrototype',
            value = 'x'
        el[propName] = value;
        isBuggy = (el.getAttribute(propName) === value);
        el = null;
        return isBuggy;
      })();

      return PROPERTIES_ATTRIBUTES_MAP ?
        function(nodes) {
          for (var i = 0, node; node = nodes[i]; i++)
            node.removeAttribute('_countedByPrototype');
          return nodes;
        } :
        function(nodes) {
          for (var i = 0, node; node = nodes[i]; i++)
            node._countedByPrototype = void 0;
          return nodes;
        }
    })(),

    index: function(parentNode, reverse, ofType) {
      parentNode._countedByPrototype = Prototype.emptyFunction;
      if (reverse) {
        for (var nodes = parentNode.childNodes, i = nodes.length - 1, j = 1; i >= 0; i--) {
          var node = nodes[i];
          if (node.nodeType == 1 && (!ofType || node._countedByPrototype)) node.nodeIndex = j++;
        }
      } else {
        for (var i = 0, j = 1, nodes = parentNode.childNodes; node = nodes[i]; i++)
          if (node.nodeType == 1 && (!ofType || node._countedByPrototype)) node.nodeIndex = j++;
      }
    },

    unique: function(nodes) {
      if (nodes.length == 0) return nodes;
      var results = [], n;
      for (var i = 0, l = nodes.length; i < l; i++)
        if (typeof (n = nodes[i])._countedByPrototype == 'undefined') {
          n._countedByPrototype = Prototype.emptyFunction;
          results.push(Element.extend(n));
        }
      return Selector.handlers.unmark(results);
    },

    descendant: function(nodes) {
      var h = Selector.handlers;
      for (var i = 0, results = [], node; node = nodes[i]; i++)
        h.concat(results, node.getElementsByTagName('*'));
      return results;
    },

    child: function(nodes) {
      var h = Selector.handlers;
      for (var i = 0, results = [], node; node = nodes[i]; i++) {
        for (var j = 0, child; child = node.childNodes[j]; j++)
          if (child.nodeType == 1 && child.tagName != '!') results.push(child);
      }
      return results;
    },

    adjacent: function(nodes) {
      for (var i = 0, results = [], node; node = nodes[i]; i++) {
        var next = this.nextElementSibling(node);
        if (next) results.push(next);
      }
      return results;
    },

    laterSibling: function(nodes) {
      var h = Selector.handlers;
      for (var i = 0, results = [], node; node = nodes[i]; i++)
        h.concat(results, Element.nextSiblings(node));
      return results;
    },

    nextElementSibling: function(node) {
      while (node = node.nextSibling)
        if (node.nodeType == 1) return node;
      return null;
    },

    previousElementSibling: function(node) {
      while (node = node.previousSibling)
        if (node.nodeType == 1) return node;
      return null;
    },

    tagName: function(nodes, root, tagName, combinator) {
      var uTagName = tagName.toUpperCase();
      var results = [], h = Selector.handlers;
      if (nodes) {
        if (combinator) {
          if (combinator == "descendant") {
            for (var i = 0, node; node = nodes[i]; i++)
              h.concat(results, node.getElementsByTagName(tagName));
            return results;
          } else nodes = this[combinator](nodes);
          if (tagName == "*") return nodes;
        }
        for (var i = 0, node; node = nodes[i]; i++)
          if (node.tagName.toUpperCase() === uTagName) results.push(node);
        return results;
      } else return root.getElementsByTagName(tagName);
    },

    id: function(nodes, root, id, combinator) {
      var targetNode = $(id), h = Selector.handlers;

      if (root == document) {
        if (!targetNode) return [];
        if (!nodes) return [targetNode];
      } else {
        if (!root.sourceIndex || root.sourceIndex < 1) {
          var nodes = root.getElementsByTagName('*');
          for (var j = 0, node; node = nodes[j]; j++) {
            if (node.id === id) return [node];
          }
        }
      }

      if (nodes) {
        if (combinator) {
          if (combinator == 'child') {
            for (var i = 0, node; node = nodes[i]; i++)
              if (targetNode.parentNode == node) return [targetNode];
          } else if (combinator == 'descendant') {
            for (var i = 0, node; node = nodes[i]; i++)
              if (Element.descendantOf(targetNode, node)) return [targetNode];
          } else if (combinator == 'adjacent') {
            for (var i = 0, node; node = nodes[i]; i++)
              if (Selector.handlers.previousElementSibling(targetNode) == node)
                return [targetNode];
          } else nodes = h[combinator](nodes);
        }
        for (var i = 0, node; node = nodes[i]; i++)
          if (node == targetNode) return [targetNode];
        return [];
      }
      return (targetNode && Element.descendantOf(targetNode, root)) ? [targetNode] : [];
    },

    className: function(nodes, root, className, combinator) {
      if (nodes && combinator) nodes = this[combinator](nodes);
      return Selector.handlers.byClassName(nodes, root, className);
    },

    byClassName: function(nodes, root, className) {
      if (!nodes) nodes = Selector.handlers.descendant([root]);
      var needle = ' ' + className + ' ';
      for (var i = 0, results = [], node, nodeClassName; node = nodes[i]; i++) {
        nodeClassName = node.className;
        if (nodeClassName.length == 0) continue;
        if (nodeClassName == className || (' ' + nodeClassName + ' ').include(needle))
          results.push(node);
      }
      return results;
    },

    attrPresence: function(nodes, root, attr, combinator) {
      if (!nodes) nodes = root.getElementsByTagName("*");
      if (nodes && combinator) nodes = this[combinator](nodes);
      var results = [];
      for (var i = 0, node; node = nodes[i]; i++)
        if (Element.hasAttribute(node, attr)) results.push(node);
      return results;
    },

    attr: function(nodes, root, attr, value, operator, combinator) {
      if (!nodes) nodes = root.getElementsByTagName("*");
      if (nodes && combinator) nodes = this[combinator](nodes);
      var handler = Selector.operators[operator], results = [];
      for (var i = 0, node; node = nodes[i]; i++) {
        var nodeValue = Element.readAttribute(node, attr);
        if (nodeValue === null) continue;
        if (handler(nodeValue, value)) results.push(node);
      }
      return results;
    },

    pseudo: function(nodes, name, value, root, combinator) {
      if (nodes && combinator) nodes = this[combinator](nodes);
      if (!nodes) nodes = root.getElementsByTagName("*");
      return Selector.pseudos[name](nodes, value, root);
    }
  },

  pseudos: {
    'first-child': function(nodes, value, root) {
      for (var i = 0, results = [], node; node = nodes[i]; i++) {
        if (Selector.handlers.previousElementSibling(node)) continue;
          results.push(node);
      }
      return results;
    },
    'last-child': function(nodes, value, root) {
      for (var i = 0, results = [], node; node = nodes[i]; i++) {
        if (Selector.handlers.nextElementSibling(node)) continue;
          results.push(node);
      }
      return results;
    },
    'only-child': function(nodes, value, root) {
      var h = Selector.handlers;
      for (var i = 0, results = [], node; node = nodes[i]; i++)
        if (!h.previousElementSibling(node) && !h.nextElementSibling(node))
          results.push(node);
      return results;
    },
    'nth-child':        function(nodes, formula, root) {
      return Selector.pseudos.nth(nodes, formula, root);
    },
    'nth-last-child':   function(nodes, formula, root) {
      return Selector.pseudos.nth(nodes, formula, root, true);
    },
    'nth-of-type':      function(nodes, formula, root) {
      return Selector.pseudos.nth(nodes, formula, root, false, true);
    },
    'nth-last-of-type': function(nodes, formula, root) {
      return Selector.pseudos.nth(nodes, formula, root, true, true);
    },
    'first-of-type':    function(nodes, formula, root) {
      return Selector.pseudos.nth(nodes, "1", root, false, true);
    },
    'last-of-type':     function(nodes, formula, root) {
      return Selector.pseudos.nth(nodes, "1", root, true, true);
    },
    'only-of-type':     function(nodes, formula, root) {
      var p = Selector.pseudos;
      return p['last-of-type'](p['first-of-type'](nodes, formula, root), formula, root);
    },

    getIndices: function(a, b, total) {
      if (a == 0) return b > 0 ? [b] : [];
      return $R(1, total).inject([], function(memo, i) {
        if (0 == (i - b) % a && (i - b) / a >= 0) memo.push(i);
        return memo;
      });
    },

    nth: function(nodes, formula, root, reverse, ofType) {
      if (nodes.length == 0) return [];
      if (formula == 'even') formula = '2n+0';
      if (formula == 'odd')  formula = '2n+1';
      var h = Selector.handlers, results = [], indexed = [], m;
      h.mark(nodes);
      for (var i = 0, node; node = nodes[i]; i++) {
        if (!node.parentNode._countedByPrototype) {
          h.index(node.parentNode, reverse, ofType);
          indexed.push(node.parentNode);
        }
      }
      if (formula.match(/^\d+$/)) { // just a number
        formula = Number(formula);
        for (var i = 0, node; node = nodes[i]; i++)
          if (node.nodeIndex == formula) results.push(node);
      } else if (m = formula.match(/^(-?\d*)?n(([+-])(\d+))?/)) { // an+b
        if (m[1] == "-") m[1] = -1;
        var a = m[1] ? Number(m[1]) : 1;
        var b = m[2] ? Number(m[2]) : 0;
        var indices = Selector.pseudos.getIndices(a, b, nodes.length);
        for (var i = 0, node, l = indices.length; node = nodes[i]; i++) {
          for (var j = 0; j < l; j++)
            if (node.nodeIndex == indices[j]) results.push(node);
        }
      }
      h.unmark(nodes);
      h.unmark(indexed);
      return results;
    },

    'empty': function(nodes, value, root) {
      for (var i = 0, results = [], node; node = nodes[i]; i++) {
        if (node.tagName == '!' || node.firstChild) continue;
        results.push(node);
      }
      return results;
    },

    'not': function(nodes, selector, root) {
      var h = Selector.handlers, selectorType, m;
      var exclusions = new Selector(selector).findElements(root);
      h.mark(exclusions);
      for (var i = 0, results = [], node; node = nodes[i]; i++)
        if (!node._countedByPrototype) results.push(node);
      h.unmark(exclusions);
      return results;
    },

    'enabled': function(nodes, value, root) {
      for (var i = 0, results = [], node; node = nodes[i]; i++)
        if (!node.disabled && (!node.type || node.type !== 'hidden'))
          results.push(node);
      return results;
    },

    'disabled': function(nodes, value, root) {
      for (var i = 0, results = [], node; node = nodes[i]; i++)
        if (node.disabled) results.push(node);
      return results;
    },

    'checked': function(nodes, value, root) {
      for (var i = 0, results = [], node; node = nodes[i]; i++)
        if (node.checked) results.push(node);
      return results;
    }
  },

  operators: {
    '=':  function(nv, v) { return nv == v; },
    '!=': function(nv, v) { return nv != v; },
    '^=': function(nv, v) { return nv == v || nv && nv.startsWith(v); },
    '$=': function(nv, v) { return nv == v || nv && nv.endsWith(v); },
    '*=': function(nv, v) { return nv == v || nv && nv.include(v); },
    '~=': function(nv, v) { return (' ' + nv + ' ').include(' ' + v + ' '); },
    '|=': function(nv, v) { return ('-' + (nv || "").toUpperCase() +
     '-').include('-' + (v || "").toUpperCase() + '-'); }
  },

  split: function(expression) {
    var expressions = [];
    expression.scan(/(([\w#:.~>+()\s-]+|\*|\[.*?\])+)\s*(,|$)/, function(m) {
      expressions.push(m[1].strip());
    });
    return expressions;
  },

  matchElements: function(elements, expression) {
    var matches = $$(expression), h = Selector.handlers;
    h.mark(matches);
    for (var i = 0, results = [], element; element = elements[i]; i++)
      if (element._countedByPrototype) results.push(element);
    h.unmark(matches);
    return results;
  },

  findElement: function(elements, expression, index) {
    if (Object.isNumber(expression)) {
      index = expression; expression = false;
    }
    return Selector.matchElements(elements, expression || '*')[index || 0];
  },

  findChildElements: function(element, expressions) {
    expressions = Selector.split(expressions.join(','));
    var results = [], h = Selector.handlers;
    for (var i = 0, l = expressions.length, selector; i < l; i++) {
      selector = new Selector(expressions[i].strip());
      h.concat(results, selector.findElements(element));
    }
    return (l > 1) ? h.unique(results) : results;
  }
});

if (Prototype.Browser.IE) {
  Object.extend(Selector.handlers, {
    concat: function(a, b) {
      for (var i = 0, node; node = b[i]; i++)
        if (node.tagName !== "!") a.push(node);
      return a;
    }
  });
}

function $$() {
  return Selector.findChildElements(document, $A(arguments));
}

var Form = {
  reset: function(form) {
    form = $(form);
    form.reset();
    return form;
  },

  serializeElements: function(elements, options) {
    if (typeof options != 'object') options = { hash: !!options };
    else if (Object.isUndefined(options.hash)) options.hash = true;
    var key, value, submitted = false, submit = options.submit;

    var data = elements.inject({ }, function(result, element) {
      if (!element.disabled && element.name) {
        key = element.name; value = $(element).getValue();
        if (value != null && element.type != 'file' && (element.type != 'submit' || (!submitted &&
            submit !== false && (!submit || key == submit) && (submitted = true)))) {
          if (key in result) {
            if (!Object.isArray(result[key])) result[key] = [result[key]];
            result[key].push(value);
          }
          else result[key] = value;
        }
      }
      return result;
    });

    return options.hash ? data : Object.toQueryString(data);
  }
};

Form.Methods = {
  serialize: function(form, options) {
    return Form.serializeElements(Form.getElements(form), options);
  },

  getElements: function(form) {
    var elements = $(form).getElementsByTagName('*'),
        element,
        arr = [ ],
        serializers = Form.Element.Serializers;
    for (var i = 0; element = elements[i]; i++) {
      arr.push(element);
    }
    return arr.inject([], function(elements, child) {
      if (serializers[child.tagName.toLowerCase()])
        elements.push(Element.extend(child));
      return elements;
    })
  },

  getInputs: function(form, typeName, name) {
    form = $(form);
    var inputs = form.getElementsByTagName('input');

    if (!typeName && !name) return $A(inputs).map(Element.extend);

    for (var i = 0, matchingInputs = [], length = inputs.length; i < length; i++) {
      var input = inputs[i];
      if ((typeName && input.type != typeName) || (name && input.name != name))
        continue;
      matchingInputs.push(Element.extend(input));
    }

    return matchingInputs;
  },

  disable: function(form) {
    form = $(form);
    Form.getElements(form).invoke('disable');
    return form;
  },

  enable: function(form) {
    form = $(form);
    Form.getElements(form).invoke('enable');
    return form;
  },

  findFirstElement: function(form) {
    var elements = $(form).getElements().findAll(function(element) {
      return 'hidden' != element.type && !element.disabled;
    });
    var firstByIndex = elements.findAll(function(element) {
      return element.hasAttribute('tabIndex') && element.tabIndex >= 0;
    }).sortBy(function(element) { return element.tabIndex }).first();

    return firstByIndex ? firstByIndex : elements.find(function(element) {
      return /^(?:input|select|textarea)$/i.test(element.tagName);
    });
  },

  focusFirstElement: function(form) {
    form = $(form);
    form.findFirstElement().activate();
    return form;
  },

  request: function(form, options) {
    form = $(form), options = Object.clone(options || { });

    var params = options.parameters, action = form.readAttribute('action') || '';
    if (action.blank()) action = window.location.href;
    options.parameters = form.serialize(true);

    if (params) {
      if (Object.isString(params)) params = params.toQueryParams();
      Object.extend(options.parameters, params);
    }

    if (form.hasAttribute('method') && !options.method)
      options.method = form.method;

    return new Ajax.Request(action, options);
  }
};

/*--------------------------------------------------------------------------*/


Form.Element = {
  focus: function(element) {
    $(element).focus();
    return element;
  },

  select: function(element) {
    $(element).select();
    return element;
  }
};

Form.Element.Methods = {

  serialize: function(element) {
    element = $(element);
    if (!element.disabled && element.name) {
      var value = element.getValue();
      if (value != undefined) {
        var pair = { };
        pair[element.name] = value;
        return Object.toQueryString(pair);
      }
    }
    return '';
  },

  getValue: function(element) {
    element = $(element);
    var method = element.tagName.toLowerCase();
    return Form.Element.Serializers[method](element);
  },

  setValue: function(element, value) {
    element = $(element);
    var method = element.tagName.toLowerCase();
    Form.Element.Serializers[method](element, value);
    return element;
  },

  clear: function(element) {
    $(element).value = '';
    return element;
  },

  present: function(element) {
    return $(element).value != '';
  },

  activate: function(element) {
    element = $(element);
    try {
      element.focus();
      if (element.select && (element.tagName.toLowerCase() != 'input' ||
          !(/^(?:button|reset|submit)$/i.test(element.type))))
        element.select();
    } catch (e) { }
    return element;
  },

  disable: function(element) {
    element = $(element);
    element.disabled = true;
    return element;
  },

  enable: function(element) {
    element = $(element);
    element.disabled = false;
    return element;
  }
};

/*--------------------------------------------------------------------------*/

var Field = Form.Element;

var $F = Form.Element.Methods.getValue;

/*--------------------------------------------------------------------------*/

Form.Element.Serializers = {
  input: function(element, value) {
    switch (element.type.toLowerCase()) {
      case 'checkbox':
      case 'radio':
        return Form.Element.Serializers.inputSelector(element, value);
      default:
        return Form.Element.Serializers.textarea(element, value);
    }
  },

  inputSelector: function(element, value) {
    if (Object.isUndefined(value)) return element.checked ? element.value : null;
    else element.checked = !!value;
  },

  textarea: function(element, value) {
    if (Object.isUndefined(value)) return element.value;
    else element.value = value;
  },

  select: function(element, value) {
    if (Object.isUndefined(value))
      return this[element.type == 'select-one' ?
        'selectOne' : 'selectMany'](element);
    else {
      var opt, currentValue, single = !Object.isArray(value);
      for (var i = 0, length = element.length; i < length; i++) {
        opt = element.options[i];
        currentValue = this.optionValue(opt);
        if (single) {
          if (currentValue == value) {
            opt.selected = true;
            return;
          }
        }
        else opt.selected = value.include(currentValue);
      }
    }
  },

  selectOne: function(element) {
    var index = element.selectedIndex;
    return index >= 0 ? this.optionValue(element.options[index]) : null;
  },

  selectMany: function(element) {
    var values, length = element.length;
    if (!length) return null;

    for (var i = 0, values = []; i < length; i++) {
      var opt = element.options[i];
      if (opt.selected) values.push(this.optionValue(opt));
    }
    return values;
  },

  optionValue: function(opt) {
    return Element.extend(opt).hasAttribute('value') ? opt.value : opt.text;
  }
};

/*--------------------------------------------------------------------------*/


Abstract.TimedObserver = Class.create(PeriodicalExecuter, {
  initialize: function($super, element, frequency, callback) {
    $super(callback, frequency);
    this.element   = $(element);
    this.lastValue = this.getValue();
  },

  execute: function() {
    var value = this.getValue();
    if (Object.isString(this.lastValue) && Object.isString(value) ?
        this.lastValue != value : String(this.lastValue) != String(value)) {
      this.callback(this.element, value);
      this.lastValue = value;
    }
  }
});

Form.Element.Observer = Class.create(Abstract.TimedObserver, {
  getValue: function() {
    return Form.Element.getValue(this.element);
  }
});

Form.Observer = Class.create(Abstract.TimedObserver, {
  getValue: function() {
    return Form.serialize(this.element);
  }
});

/*--------------------------------------------------------------------------*/

Abstract.EventObserver = Class.create({
    initialize: function(element, callback, event) {
    this.element  = $(element);
    this.callback = callback;
        this.event = event || 'change'; // mhollauf mindmeister fix - not ignore the parameter for observe_field!

    this.lastValue = this.getValue();
    if (this.element.tagName.toLowerCase() == 'form')
      this.registerFormCallbacks();
    else
      this.registerCallback(this.element);
  },

  onElementEvent: function() {
    var value = this.getValue();
    if (this.lastValue != value) {
      this.callback(this.element, value);
      this.lastValue = value;
    }
  },

  registerFormCallbacks: function() {
    Form.getElements(this.element).each(this.registerCallback, this);
  },

  registerCallback: function(element) {
    if (element.type) {
      switch (element.type.toLowerCase()) {
        case 'checkbox':
        case 'radio':
          Event.observe(element, 'click', this.onElementEvent.bind(this));
          break;
        default:
          Event.observe(element, 'change', this.onElementEvent.bind(this));
          break;
      }
    }
  }
});

Form.Element.EventObserver = Class.create(Abstract.EventObserver, {
  getValue: function() {
    return Form.Element.getValue(this.element);
  }
});

Form.EventObserver = Class.create(Abstract.EventObserver, {
  getValue: function() {
    return Form.serialize(this.element);
  }
});
(function() {

  var Event = {
    KEY_BACKSPACE: 8,
    KEY_TAB:       9,
    KEY_RETURN:   13,
    KEY_ESC:      27,
    KEY_LEFT:     37,
    KEY_UP:       38,
    KEY_RIGHT:    39,
    KEY_DOWN:     40,
    KEY_DELETE:   46,
    KEY_HOME:     36,
    KEY_END:      35,
    KEY_PAGEUP:   33,
    KEY_PAGEDOWN: 34,
    KEY_INSERT:   45,

    cache: {}
  };

  var docEl = document.documentElement;
  var MOUSEENTER_MOUSELEAVE_EVENTS_SUPPORTED = 'onmouseenter' in docEl
    && 'onmouseleave' in docEl;

  var _isButton;
  if (Prototype.Browser.IE) {
    var buttonMap = { 0: 1, 1: 4, 2: 2 };
    _isButton = function(event, code) {
      return event.button === buttonMap[code];
    };
  } else if (Prototype.Browser.WebKit) {
    _isButton = function(event, code) {
      switch (code) {
        case 0: return event.which == 1 && !event.metaKey;
        case 1: return event.which == 1 && event.metaKey;
        default: return false;
      }
    };
  } else {
    _isButton = function(event, code) {
      return event.which ? (event.which === code + 1) : (event.button === code);
    };
  }

  function isLeftClick(event)   { return _isButton(event, 0) }

  function isMiddleClick(event) { return _isButton(event, 1) }

  function isRightClick(event)  { return _isButton(event, 2) }

  function element(event) {
    event = Event.extend(event);

    var node = event.target, type = event.type,
     currentTarget = event.currentTarget;

    if (currentTarget && currentTarget.tagName) {
      if (type === 'load' || type === 'error' ||
        (type === 'click' && currentTarget.tagName.toLowerCase() === 'input'
          && currentTarget.type === 'radio'))
            node = currentTarget;
    }

    if (node.nodeType == Node.TEXT_NODE)
      node = node.parentNode;

    return Element.extend(node);
  }

  function findElement(event, expression) {
    var element = Event.element(event);
    if (!expression) return element;
    var elements = [element].concat(element.ancestors());
    return Selector.findElement(elements, expression, 0);
  }

  function pointer(event) {
    return { x: pointerX(event), y: pointerY(event) };
  }

  function pointerX(event) {
    var docElement = document.documentElement,
     body = document.body || { scrollLeft: 0 };

    return event.pageX || (event.clientX +
      (docElement.scrollLeft || body.scrollLeft) -
      (docElement.clientLeft || 0));
  }

  function pointerY(event) {
    var docElement = document.documentElement,
     body = document.body || { scrollTop: 0 };

    return  event.pageY || (event.clientY +
       (docElement.scrollTop || body.scrollTop) -
       (docElement.clientTop || 0));
  }


  function stop(event) {
    Event.extend(event);
    event.preventDefault();
    event.stopPropagation();

    event.stopped = true;
  }

  Event.Methods = {
    isLeftClick: isLeftClick,
    isMiddleClick: isMiddleClick,
    isRightClick: isRightClick,

    element: element,
    findElement: findElement,

    pointer: pointer,
    pointerX: pointerX,
    pointerY: pointerY,

    stop: stop
  };


  var methods = Object.keys(Event.Methods).inject({ }, function(m, name) {
    m[name] = Event.Methods[name].methodize();
    return m;
  });

  if (Prototype.Browser.IE) {
    function _relatedTarget(event) {
      var element;
      switch (event.type) {
        case 'mouseover': element = event.fromElement; break;
        case 'mouseout':  element = event.toElement;   break;
        default: return null;
      }
      return Element.extend(element);
    }

    Object.extend(methods, {
      stopPropagation: function() { this.cancelBubble = true },
      preventDefault:  function() { this.returnValue = false },
      inspect: function() { return '[object Event]' }
    });

    Event.extend = function(event, element) {
      if (!event) return false;
      if (event._extendedByPrototype) return event;

      event._extendedByPrototype = Prototype.emptyFunction;
      var pointer = Event.pointer(event);

      Object.extend(event, {
        target: event.srcElement || element,
        relatedTarget: _relatedTarget(event),
        pageX:  pointer.x,
        pageY:  pointer.y
      });

      return Object.extend(event, methods);
    };
  } else {
    Event.prototype = window.Event.prototype || document.createEvent('HTMLEvents').__proto__;
    Object.extend(Event.prototype, methods);
    Event.extend = Prototype.K;
  }

  function _createResponder(element, eventName, handler) {
    var registry = Element.retrieve(element, 'prototype_event_registry');

    if (Object.isUndefined(registry)) {
      CACHE.push(element);
      registry = Element.retrieve(element, 'prototype_event_registry', $H());
    }

    var respondersForEvent = registry.get(eventName);
    if (Object.isUndefined(respondersForEvent)) {
      respondersForEvent = [];
      registry.set(eventName, respondersForEvent);
    }

    if (respondersForEvent.pluck('handler').include(handler)) return false;

    var responder;
    if (eventName.include(":")) {
      responder = function(event) {
        if (Object.isUndefined(event.eventName))
          return false;

        if (event.eventName !== eventName)
          return false;

        Event.extend(event, element);
        handler.call(element, event);
      };
    } else {
      if (!MOUSEENTER_MOUSELEAVE_EVENTS_SUPPORTED &&
       (eventName === "mouseenter" || eventName === "mouseleave")) {
        if (eventName === "mouseenter" || eventName === "mouseleave") {
          responder = function(event) {
            Event.extend(event, element);

            var parent = event.relatedTarget;
            while (parent && parent !== element) {
              try { parent = parent.parentNode; }
              catch(e) { parent = element; }
            }

            if (parent === element) return;

            handler.call(element, event);
          };
        }
      } else {
        responder = function(event) {
          Event.extend(event, element);
          handler.call(element, event);
        };
      }
    }

    responder.handler = handler;
    respondersForEvent.push(responder);
    return responder;
  }

  function _destroyCache() {
    for (var i = 0, length = CACHE.length; i < length; i++) {
      Event.stopObserving(CACHE[i]);
      CACHE[i] = null;
    }
  }

  var CACHE = [];

  if (Prototype.Browser.IE)
    window.attachEvent('onunload', _destroyCache);

  if (Prototype.Browser.WebKit)
    window.addEventListener('unload', Prototype.emptyFunction, false);


  var _getDOMEventName = Prototype.K;

  if (!MOUSEENTER_MOUSELEAVE_EVENTS_SUPPORTED) {
    _getDOMEventName = function(eventName) {
      var translations = { mouseenter: "mouseover", mouseleave: "mouseout" };
      return eventName in translations ? translations[eventName] : eventName;
    };
  }

  function observe(element, eventName, handler) {
    element = $(element);

    var responder = _createResponder(element, eventName, handler);

    if (!responder) return element;

    if (eventName.include(':')) {
      if (element.addEventListener)
        element.addEventListener("dataavailable", responder, false);
      else {
        element.attachEvent("ondataavailable", responder);
        element.attachEvent("onfilterchange", responder);
      }
    } else {
      var actualEventName = _getDOMEventName(eventName);

      if (element.addEventListener)
        element.addEventListener(actualEventName, responder, false);
      else
        element.attachEvent("on" + actualEventName, responder);
    }

    return element;
  }

  function stopObserving(element, eventName, handler) {
    element = $(element);

    var registry = Element.retrieve(element, 'prototype_event_registry');

    if (Object.isUndefined(registry)) return element;

    if (eventName && !handler) {
      var responders = registry.get(eventName);

      if (Object.isUndefined(responders)) return element;

      responders.each( function(r) {
        Element.stopObserving(element, eventName, r.handler);
      });
      return element;
    } else if (!eventName) {
      registry.each( function(pair) {
        var eventName = pair.key, responders = pair.value;

        responders.each( function(r) {
          Element.stopObserving(element, eventName, r.handler);
        });
      });
      return element;
    }

    var responders = registry.get(eventName);

    if (!responders) return;

    var responder = responders.find( function(r) { return r.handler === handler; });
    if (!responder) return element;

    var actualEventName = _getDOMEventName(eventName);

    if (eventName.include(':')) {
      if (element.removeEventListener)
        element.removeEventListener("dataavailable", responder, false);
      else {
        element.detachEvent("ondataavailable", responder);
        element.detachEvent("onfilterchange",  responder);
      }
    } else {
      if (element.removeEventListener)
        element.removeEventListener(actualEventName, responder, false);
      else
        element.detachEvent('on' + actualEventName, responder);
    }

    registry.set(eventName, responders.without(responder));

    return element;
  }

  function fire(element, eventName, memo, bubble) {
    element = $(element);

    if (Object.isUndefined(bubble))
      bubble = true;

    if (element == document && document.createEvent && !element.dispatchEvent)
      element = document.documentElement;

    var event;
    if (document.createEvent) {
      event = document.createEvent('HTMLEvents');
      event.initEvent('dataavailable', true, true);
    } else {
      event = document.createEventObject();
      event.eventType = bubble ? 'ondataavailable' : 'onfilterchange';
    }

    event.eventName = eventName;
    event.memo = memo || { };

    if (document.createEvent)
      element.dispatchEvent(event);
    else
      element.fireEvent(event.eventType, event);

    return Event.extend(event);
  }


  Object.extend(Event, Event.Methods);

  Object.extend(Event, {
    fire:          fire,
    observe:       observe,
    stopObserving: stopObserving
  });

  Element.addMethods({
    fire:          fire,

    observe:       observe,

    stopObserving: stopObserving
  });

  Object.extend(document, {
    fire:          fire.methodize(),

    observe:       observe.methodize(),

    stopObserving: stopObserving.methodize(),

    loaded:        false
  });

  if (window.Event) Object.extend(window.Event, Event);
  else window.Event = Event;
})();

(function() {
  /* Support for the DOMContentLoaded event is based on work by Dan Webb,
     Matthias Miller, Dean Edwards, John Resig, and Diego Perini. */

  var timer;

  function fireContentLoadedEvent() {
    if (document.loaded) return;
    if (timer) window.clearTimeout(timer);
    document.loaded = true;
    document.fire('dom:loaded');
  }

  function checkReadyState() {
    if (document.readyState === 'complete') {
      document.stopObserving('readystatechange', checkReadyState);
      fireContentLoadedEvent();
    }
  }

  function pollDoScroll() {
    try { document.documentElement.doScroll('left'); }
    catch(e) {
      timer = pollDoScroll.defer();
      return;
    }
    fireContentLoadedEvent();
  }

  if (document.addEventListener) {
    document.addEventListener('DOMContentLoaded', fireContentLoadedEvent, false);
  } else {
    document.observe('readystatechange', checkReadyState);
    if (window == top)
      timer = pollDoScroll.defer();
  }

  Event.observe(window, 'load', fireContentLoadedEvent);
})();

Element.addMethods();

/*------------------------------- DEPRECATED -------------------------------*/

Hash.toQueryString = Object.toQueryString;

var Toggle = { display: Element.toggle };

Element.Methods.childOf = Element.Methods.descendantOf;

var Insertion = {
  Before: function(element, content) {
    return Element.insert(element, {before:content});
  },

  Top: function(element, content) {
    return Element.insert(element, {top:content});
  },

  Bottom: function(element, content) {
    return Element.insert(element, {bottom:content});
  },

  After: function(element, content) {
    return Element.insert(element, {after:content});
  }
};

var $continue = new Error('"throw $continue" is deprecated, use "return" instead');

var Position = {
  includeScrollOffsets: false,

  prepare: function() {
    this.deltaX =  window.pageXOffset
                || document.documentElement.scrollLeft
                || document.body.scrollLeft
                || 0;
    this.deltaY =  window.pageYOffset
                || document.documentElement.scrollTop
                || document.body.scrollTop
                || 0;
  },

  within: function(element, x, y) {
    if (this.includeScrollOffsets)
      return this.withinIncludingScrolloffsets(element, x, y);
    this.xcomp = x;
    this.ycomp = y;
    this.offset = Element.cumulativeOffset(element);

    return (y >= this.offset[1] &&
            y <  this.offset[1] + element.offsetHeight &&
            x >= this.offset[0] &&
            x <  this.offset[0] + element.offsetWidth);
  },

  withinIncludingScrolloffsets: function(element, x, y) {
    var offsetcache = Element.cumulativeScrollOffset(element);

    this.xcomp = x + offsetcache[0] - this.deltaX;
    this.ycomp = y + offsetcache[1] - this.deltaY;
    this.offset = Element.cumulativeOffset(element);

    return (this.ycomp >= this.offset[1] &&
            this.ycomp <  this.offset[1] + element.offsetHeight &&
            this.xcomp >= this.offset[0] &&
            this.xcomp <  this.offset[0] + element.offsetWidth);
  },

  overlap: function(mode, element) {
    if (!mode) return 0;
    if (mode == 'vertical')
      return ((this.offset[1] + element.offsetHeight) - this.ycomp) /
        element.offsetHeight;
    if (mode == 'horizontal')
      return ((this.offset[0] + element.offsetWidth) - this.xcomp) /
        element.offsetWidth;
  },


  cumulativeOffset: Element.Methods.cumulativeOffset,

  positionedOffset: Element.Methods.positionedOffset,

  absolutize: function(element) {
    Position.prepare();
    return Element.absolutize(element);
  },

  relativize: function(element) {
    Position.prepare();
    return Element.relativize(element);
  },

  realOffset: Element.Methods.cumulativeScrollOffset,

  offsetParent: Element.Methods.getOffsetParent,

  page: Element.Methods.viewportOffset,

  clone: function(source, target, options) {
    options = options || { };
    return Element.clonePosition(target, source, options);
  }
};

/*--------------------------------------------------------------------------*/

if (!document.getElementsByClassName) document.getElementsByClassName = function(instanceMethods){
  function iter(name) {
    return name.blank() ? null : "[contains(concat(' ', @class, ' '), ' " + name + " ')]";
  }

  instanceMethods.getElementsByClassName = Prototype.BrowserFeatures.XPath ?
  function(element, className) {
    className = className.toString().strip();
    var cond = /\s/.test(className) ? $w(className).map(iter).join('') : iter(className);
    return cond ? document._getElementsByXPath('.//*' + cond, element) : [];
  } : function(element, className) {
    className = className.toString().strip();
    var elements = [], classNames = (/\s/.test(className) ? $w(className) : null);
    if (!classNames && !className) return elements;

    var nodes = $(element).getElementsByTagName('*');
    className = ' ' + className + ' ';

    for (var i = 0, child, cn; child = nodes[i]; i++) {
      if (child.className && (cn = ' ' + child.className + ' ') && (cn.include(className) ||
          (classNames && classNames.all(function(name) {
            return !name.toString().blank() && cn.include(' ' + name + ' ');
          }))))
        elements.push(Element.extend(child));
    }
    return elements;
  };

  return function(className, parentElement) {
    return $(parentElement || document.body).getElementsByClassName(className);
  };
}(Element.Methods);

/*--------------------------------------------------------------------------*/

Element.ClassNames = Class.create();
Element.ClassNames.prototype = {
  initialize: function(element) {
    this.element = $(element);
  },

  _each: function(iterator) {
    this.element.className.split(/\s+/).select(function(name) {
      return name.length > 0;
    })._each(iterator);
  },

  set: function(className) {
    this.element.className = className;
  },

  add: function(classNameToAdd) {
    if (this.include(classNameToAdd)) return;
    this.set($A(this).concat(classNameToAdd).join(' '));
  },

  remove: function(classNameToRemove) {
    if (!this.include(classNameToRemove)) return;
    this.set($A(this).without(classNameToRemove).join(' '));
  },

  toString: function() {
    return $A(this).join(' ');
  }
};

Object.extend(Element.ClassNames.prototype, Enumerable);

/*--------------------------------------------------------------------------*/
// script.aculo.us effects.js v1.8.3, Thu Oct 08 11:23:33 +0200 2009

// Copyright (c) 2005-2009 Thomas Fuchs (http://script.aculo.us, http://mir.aculo.us)
// Contributors:
//  Justin Palmer (http://encytemedia.com/)
//  Mark Pilgrim (http://diveintomark.org/)
//  Martin Bialasinki
//
// script.aculo.us is freely distributable under the terms of an MIT-style license.
// For details, see the script.aculo.us web site: http://script.aculo.us/

// converts rgb() and #xxx to #xxxxxx format,
// returns self (or first argument) if not convertable
String.prototype.parseColor = function() {
  var color = '#';
  if (this.slice(0,4) == 'rgb(') {
    var cols = this.slice(4,this.length-1).split(',');
    var i=0; do { color += parseInt(cols[i]).toColorPart() } while (++i<3);
  } else {
    if (this.slice(0,1) == '#') {
      if (this.length==4) for(var i=1;i<4;i++) color += (this.charAt(i) + this.charAt(i)).toLowerCase();
      if (this.length==7) color = this.toLowerCase();
    }
  }
  return (color.length==7 ? color : (arguments[0] || this));
};

/*--------------------------------------------------------------------------*/

Element.collectTextNodes = function(element) {
  return $A($(element).childNodes).collect( function(node) {
    return (node.nodeType==3 ? node.nodeValue :
      (node.hasChildNodes() ? Element.collectTextNodes(node) : ''));
  }).flatten().join('');
};

Element.collectTextNodesIgnoreClass = function(element, className) {
  return $A($(element).childNodes).collect( function(node) {
    return (node.nodeType==3 ? node.nodeValue :
      ((node.hasChildNodes() && !Element.hasClassName(node,className)) ?
        Element.collectTextNodesIgnoreClass(node, className) : ''));
  }).flatten().join('');
};

Element.setContentZoom = function(element, percent) {
  element = $(element);
  element.setStyle({fontSize: (percent/100) + 'em'});
  if (Prototype.Browser.WebKit) window.scrollBy(0,0);
  return element;
};

Element.getInlineOpacity = function(element){
  return $(element).style.opacity || '';
};

Element.forceRerendering = function(element) {
  try {
    element = $(element);
    var n = document.createTextNode(' ');
    element.appendChild(n);
    element.removeChild(n);
  } catch(e) { }
};

/*--------------------------------------------------------------------------*/

var Effect = {
  _elementDoesNotExistError: {
    name: 'ElementDoesNotExistError',
    message: 'The specified DOM element does not exist, but is required for this effect to operate'
  },
  Transitions: {
    linear: Prototype.K,
    sinoidal: function(pos) {
      return (-Math.cos(pos*Math.PI)/2) + .5;
    },
    reverse: function(pos) {
      return 1-pos;
    },
    flicker: function(pos) {
      var pos = ((-Math.cos(pos*Math.PI)/4) + .75) + Math.random()/4;
      return pos > 1 ? 1 : pos;
    },
    wobble: function(pos) {
      return (-Math.cos(pos*Math.PI*(9*pos))/2) + .5;
    },
    pulse: function(pos, pulses) {
      return (-Math.cos((pos*((pulses||5)-.5)*2)*Math.PI)/2) + .5;
    },
    spring: function(pos) {
      return 1 - (Math.cos(pos * 4.5 * Math.PI) * Math.exp(-pos * 6));
    },
    none: function(pos) {
      return 0;
    },
    full: function(pos) {
      return 1;
    }
  },
  DefaultOptions: {
    duration:   1.0,   // seconds
    fps:        100,   // 100= assume 66fps max.
    sync:       false, // true for combining
    from:       0.0,
    to:         1.0,
    delay:      0.0,
    queue:      'parallel'
  },
  tagifyText: function(element) {
    var tagifyStyle = 'position:relative';
    if (Prototype.Browser.IE) tagifyStyle += ';zoom:1';

    element = $(element);
    $A(element.childNodes).each( function(child) {
      if (child.nodeType==3) {
        child.nodeValue.toArray().each( function(character) {
          element.insertBefore(
            new Element('span', {style: tagifyStyle}).update(
              character == ' ' ? String.fromCharCode(160) : character),
              child);
        });
        Element.remove(child);
      }
    });
  },
  multiple: function(element, effect) {
    var elements;
    if (((typeof element == 'object') ||
        Object.isFunction(element)) &&
       (element.length))
      elements = element;
    else
      elements = $(element).childNodes;

    var options = Object.extend({
      speed: 0.1,
      delay: 0.0
    }, arguments[2] || { });
    var masterDelay = options.delay;

    $A(elements).each( function(element, index) {
      new effect(element, Object.extend(options, { delay: index * options.speed + masterDelay }));
    });
  },
  PAIRS: {
    'slide':  ['SlideDown','SlideUp'],
    'blind':  ['BlindDown','BlindUp'],
    'appear': ['Appear','Fade']
  },
  toggle: function(element, effect, options) {
    element = $(element);
    effect  = (effect || 'appear').toLowerCase();
    
    return Effect[ Effect.PAIRS[ effect ][ element.visible() ? 1 : 0 ] ](element, Object.extend({
      queue: { position:'end', scope:(element.id || 'global'), limit: 1 }
    }, options || {}));
  }
};

Effect.DefaultOptions.transition = Effect.Transitions.sinoidal;

/* ------------- core effects ------------- */

Effect.ScopedQueue = Class.create(Enumerable, {
  initialize: function() {
    this.effects  = [];
    this.interval = null;
  },
  _each: function(iterator) {
    this.effects._each(iterator);
  },
  add: function(effect) {
    var timestamp = new Date().getTime();

    var position = Object.isString(effect.options.queue) ?
      effect.options.queue : effect.options.queue.position;

    switch(position) {
      case 'front':
        // move unstarted effects after this effect
        this.effects.findAll(function(e){ return e.state=='idle' }).each( function(e) {
            e.startOn  += effect.finishOn;
            e.finishOn += effect.finishOn;
          });
        break;
      case 'with-last':
        timestamp = this.effects.pluck('startOn').max() || timestamp;
        break;
      case 'end':
        // start effect after last queued effect has finished
        timestamp = this.effects.pluck('finishOn').max() || timestamp;
        break;
    }

    effect.startOn  += timestamp;
    effect.finishOn += timestamp;

    if (!effect.options.queue.limit || (this.effects.length < effect.options.queue.limit))
      this.effects.push(effect);

    if (!this.interval)
      this.interval = setInterval(this.loop.bind(this), 15);
  },
  remove: function(effect) {
    this.effects = this.effects.reject(function(e) { return e==effect });
    if (this.effects.length == 0) {
      clearInterval(this.interval);
      this.interval = null;
    }
  },
  loop: function() {
    var timePos = new Date().getTime();
    for(var i=0, len=this.effects.length;i<len;i++)
      this.effects[i] && this.effects[i].loop(timePos);
  }
});

Effect.Queues = {
  instances: $H(),
  get: function(queueName) {
    if (!Object.isString(queueName)) return queueName;

    return this.instances.get(queueName) ||
      this.instances.set(queueName, new Effect.ScopedQueue());
  }
};
Effect.Queue = Effect.Queues.get('global');

Effect.Base = Class.create({
  position: null,
  start: function(options) {
    if (options && options.transition === false) options.transition = Effect.Transitions.linear;
    this.options      = Object.extend(Object.extend({ },Effect.DefaultOptions), options || { });
    this.currentFrame = 0;
    this.state        = 'idle';
    this.startOn      = this.options.delay*1000;
    this.finishOn     = this.startOn+(this.options.duration*1000);
    this.fromToDelta  = this.options.to-this.options.from;
    this.totalTime    = this.finishOn-this.startOn;
    this.totalFrames  = this.options.fps*this.options.duration;

    this.render = (function() {
      function dispatch(effect, eventName) {
        if (effect.options[eventName + 'Internal'])
          effect.options[eventName + 'Internal'](effect);
        if (effect.options[eventName])
          effect.options[eventName](effect);
      }

      return function(pos) {
        if (this.state === "idle") {
          this.state = "running";
          dispatch(this, 'beforeSetup');
          if (this.setup) this.setup();
          dispatch(this, 'afterSetup');
        }
        if (this.state === "running") {
          pos = (this.options.transition(pos) * this.fromToDelta) + this.options.from;
          this.position = pos;
          dispatch(this, 'beforeUpdate');
          if (this.update) this.update(pos);
          dispatch(this, 'afterUpdate');
        }
      };
    })();

    this.event('beforeStart');
    if (!this.options.sync)
      Effect.Queues.get(Object.isString(this.options.queue) ?
        'global' : this.options.queue.scope).add(this);
  },
  loop: function(timePos) {
    if (timePos >= this.startOn) {
      if (timePos >= this.finishOn) {
        this.render(1.0);
        this.cancel();
        this.event('beforeFinish');
        if (this.finish) this.finish();
        this.event('afterFinish');
        return;
      }
      var pos   = (timePos - this.startOn) / this.totalTime,
          frame = (pos * this.totalFrames).round();
      if (frame > this.currentFrame) {
        this.render(pos);
        this.currentFrame = frame;
      }
    }
  },
  cancel: function() {
    if (!this.options.sync)
      Effect.Queues.get(Object.isString(this.options.queue) ?
        'global' : this.options.queue.scope).remove(this);
    this.state = 'finished';
  },
  event: function(eventName) {
    if (this.options[eventName + 'Internal']) this.options[eventName + 'Internal'](this);
    if (this.options[eventName]) this.options[eventName](this);
  },
  inspect: function() {
    var data = $H();
    for(property in this)
      if (!Object.isFunction(this[property])) data.set(property, this[property]);
    return '#<Effect:' + data.inspect() + ',options:' + $H(this.options).inspect() + '>';
  }
});

Effect.Parallel = Class.create(Effect.Base, {
  initialize: function(effects) {
    this.effects = effects || [];
    this.start(arguments[1]);
  },
  update: function(position) {
    this.effects.invoke('render', position);
  },
  finish: function(position) {
    this.effects.each( function(effect) {
      effect.render(1.0);
      effect.cancel();
      effect.event('beforeFinish');
      if (effect.finish) effect.finish(position);
      effect.event('afterFinish');
    });
  }
});

Effect.Tween = Class.create(Effect.Base, {
  initialize: function(object, from, to) {
    object = Object.isString(object) ? $(object) : object;
    var args = $A(arguments), method = args.last(),
      options = args.length == 5 ? args[3] : null;
    this.method = Object.isFunction(method) ? method.bind(object) :
      Object.isFunction(object[method]) ? object[method].bind(object) :
      function(value) { object[method] = value };
    this.start(Object.extend({ from: from, to: to }, options || { }));
  },
  update: function(position) {
    this.method(position);
  }
});

Effect.Event = Class.create(Effect.Base, {
  initialize: function() {
    this.start(Object.extend({ duration: 0 }, arguments[0] || { }));
  },
  update: Prototype.emptyFunction
});

Effect.Opacity = Class.create(Effect.Base, {
  initialize: function(element) {
    this.element = $(element);
    if (!this.element) throw(Effect._elementDoesNotExistError);
    // make this work on IE on elements without 'layout'
    if (Prototype.Browser.IE && (!this.element.currentStyle.hasLayout))
      this.element.setStyle({zoom: 1});
    var options = Object.extend({
      from: this.element.getOpacity() || 0.0,
      to:   1.0
    }, arguments[1] || { });
    this.start(options);
  },
  update: function(position) {
    this.element.setOpacity(position);
  }
});

Effect.Move = Class.create(Effect.Base, {
  initialize: function(element) {
    this.element = $(element);
    if (!this.element) throw(Effect._elementDoesNotExistError);
    var options = Object.extend({
      x:    0,
      y:    0,
      mode: 'relative'
    }, arguments[1] || { });
    this.start(options);
  },
  setup: function() {
    this.element.makePositioned();
    this.originalLeft = parseFloat(this.element.getStyle('left') || '0');
    this.originalTop  = parseFloat(this.element.getStyle('top')  || '0');
    if (this.options.mode == 'absolute') {
      this.options.x = this.options.x - this.originalLeft;
      this.options.y = this.options.y - this.originalTop;
    }
  },
  update: function(position) {
    this.element.setStyle({
      left: (this.options.x  * position + this.originalLeft).round() + 'px',
      top:  (this.options.y  * position + this.originalTop).round()  + 'px'
    });
  }
});

// for backwards compatibility
Effect.MoveBy = function(element, toTop, toLeft) {
  return new Effect.Move(element,
    Object.extend({ x: toLeft, y: toTop }, arguments[3] || { }));
};

Effect.Scale = Class.create(Effect.Base, {
  initialize: function(element, percent) {
    this.element = $(element);
    if (!this.element) throw(Effect._elementDoesNotExistError);
    var options = Object.extend({
      scaleX: true,
      scaleY: true,
      scaleContent: true,
      scaleFromCenter: false,
      scaleMode: 'box',        // 'box' or 'contents' or { } with provided values
      scaleFrom: 100.0,
      scaleTo:   percent
    }, arguments[2] || { });
    this.start(options);
  },
  setup: function() {
    this.restoreAfterFinish = this.options.restoreAfterFinish || false;
    this.elementPositioning = this.element.getStyle('position');

    this.originalStyle = { };
    ['top','left','width','height','fontSize'].each( function(k) {
      this.originalStyle[k] = this.element.style[k];
    }.bind(this));

    this.originalTop  = this.element.offsetTop;
    this.originalLeft = this.element.offsetLeft;

    var fontSize = this.element.getStyle('font-size') || '100%';
    ['em','px','%','pt'].each( function(fontSizeType) {
      if (fontSize.indexOf(fontSizeType)>0) {
        this.fontSize     = parseFloat(fontSize);
        this.fontSizeType = fontSizeType;
      }
    }.bind(this));

    this.factor = (this.options.scaleTo - this.options.scaleFrom)/100;

    this.dims = null;
    if (this.options.scaleMode=='box')
      this.dims = [this.element.offsetHeight, this.element.offsetWidth];
    if (/^content/.test(this.options.scaleMode))
      this.dims = [this.element.scrollHeight, this.element.scrollWidth];
    if (!this.dims)
      this.dims = [this.options.scaleMode.originalHeight,
                   this.options.scaleMode.originalWidth];
  },
  update: function(position) {
    var currentScale = (this.options.scaleFrom/100.0) + (this.factor * position);
    if (this.options.scaleContent && this.fontSize)
      this.element.setStyle({fontSize: this.fontSize * currentScale + this.fontSizeType });
    this.setDimensions(this.dims[0] * currentScale, this.dims[1] * currentScale);
  },
  finish: function(position) {
    if (this.restoreAfterFinish) this.element.setStyle(this.originalStyle);
  },
  setDimensions: function(height, width) {
    var d = { };
    if (this.options.scaleX) d.width = width.round() + 'px';
    if (this.options.scaleY) d.height = height.round() + 'px';
    if (this.options.scaleFromCenter) {
      var topd  = (height - this.dims[0])/2;
      var leftd = (width  - this.dims[1])/2;
      if (this.elementPositioning == 'absolute') {
        if (this.options.scaleY) d.top = this.originalTop-topd + 'px';
        if (this.options.scaleX) d.left = this.originalLeft-leftd + 'px';
      } else {
        if (this.options.scaleY) d.top = -topd + 'px';
        if (this.options.scaleX) d.left = -leftd + 'px';
      }
    }
    this.element.setStyle(d);
  }
});

Effect.Highlight = Class.create(Effect.Base, {
  initialize: function(element) {
    this.element = $(element);
    if (!this.element) throw(Effect._elementDoesNotExistError);
    var options = Object.extend({ startcolor: '#ffff99' }, arguments[1] || { });
    this.start(options);
  },
  setup: function() {
    // Prevent executing on elements not in the layout flow
    if (this.element.getStyle('display')=='none') { this.cancel(); return; }
    // Disable background image during the effect
    this.oldStyle = { };
    if (!this.options.keepBackgroundImage) {
      this.oldStyle.backgroundImage = this.element.getStyle('background-image');
      this.element.setStyle({backgroundImage: 'none'});
    }
    if (!this.options.endcolor)
      this.options.endcolor = this.element.getStyle('background-color').parseColor('#ffffff');
    if (!this.options.restorecolor)
      this.options.restorecolor = this.element.getStyle('background-color');
    // init color calculations
    this._base  = $R(0,2).map(function(i){ return parseInt(this.options.startcolor.slice(i*2+1,i*2+3),16) }.bind(this));
    this._delta = $R(0,2).map(function(i){ return parseInt(this.options.endcolor.slice(i*2+1,i*2+3),16)-this._base[i] }.bind(this));
  },
  update: function(position) {
    this.element.setStyle({backgroundColor: $R(0,2).inject('#',function(m,v,i){
      return m+((this._base[i]+(this._delta[i]*position)).round().toColorPart()); }.bind(this)) });
  },
  finish: function() {
    this.element.setStyle(Object.extend(this.oldStyle, {
      backgroundColor: this.options.restorecolor
    }));
  }
});

Effect.ScrollTo = function(element) {
  var options = arguments[1] || { },
  scrollOffsets = document.viewport.getScrollOffsets(),
  elementOffsets = $(element).cumulativeOffset();

  if (options.offset) elementOffsets[1] += options.offset;

  return new Effect.Tween(null,
    scrollOffsets.top,
    elementOffsets[1],
    options,
    function(p){ scrollTo(scrollOffsets.left, p.round()); }
  );
};

// mhollauf added for MindMeister from http://pastie.caboo.se/36461
Effect.Scroll = Class.create();
Object.extend(Object.extend(Effect.Scroll.prototype, Effect.Base.prototype), {
  initialize: function(element) {
    this.element = $(element);
    var options = Object.extend({
      x:    0,
      y:    0,
      mode: 'absolute'
    } , arguments[1] || {}  );
    this.start(options);
  },
  setup: function() {
    if (this.options.continuous && !this.element._ext ) {
      this.element.cleanWhitespace();
      this.element._ext=true;
      this.element.appendChild(this.element.firstChild);
    }

    this.originalLeft=this.element.scrollLeft;
    this.originalTop=this.element.scrollTop;

    if(this.options.mode == 'absolute') {
      this.options.x -= this.originalLeft;
      this.options.y -= this.originalTop;
    } else {

    }
  },
  update: function(position) {
    this.element.scrollLeft = this.options.x * position + this.originalLeft;
    this.element.scrollTop  = this.options.y * position + this.originalTop;
  }
});

/* ------------- combination effects ------------- */

Effect.Fade = function(element) {
  element = $(element);
  var oldOpacity = element.getInlineOpacity();
  var options = Object.extend({
    from: element.getOpacity() || 1.0,
    to:   0.0,
    afterFinishInternal: function(effect) {
      if (effect.options.to!=0) return;
      effect.element.hide().setStyle({opacity: oldOpacity});
    }
  }, arguments[1] || { });
  return new Effect.Opacity(element,options);
};

Effect.Appear = function(element) {
  element = $(element);
  var options = Object.extend({
  from: (element.getStyle('display') == 'none' ? 0.0 : element.getOpacity() || 0.0),
  to:   1.0,
  // force Safari to render floated elements properly
  afterFinishInternal: function(effect) {
    effect.element.forceRerendering();
  },
  beforeSetup: function(effect) {
    effect.element.setOpacity(effect.options.from).show();
  }}, arguments[1] || { });
  return new Effect.Opacity(element,options);
};

Effect.Puff = function(element) {
  element = $(element);
  var oldStyle = {
    opacity: element.getInlineOpacity(),
    position: element.getStyle('position'),
    top:  element.style.top,
    left: element.style.left,
    width: element.style.width,
    height: element.style.height
  };
  return new Effect.Parallel(
   [ new Effect.Scale(element, 200,
      { sync: true, scaleFromCenter: true, scaleContent: true, restoreAfterFinish: true }),
     new Effect.Opacity(element, { sync: true, to: 0.0 } ) ],
     Object.extend({ duration: 1.0,
      beforeSetupInternal: function(effect) {
        Position.absolutize(effect.effects[0].element);
      },
      afterFinishInternal: function(effect) {
         effect.effects[0].element.hide().setStyle(oldStyle); }
     }, arguments[1] || { })
   );
};

Effect.BlindUp = function(element) {
  element = $(element);
  element.makeClipping();
  return new Effect.Scale(element, 0,
    Object.extend({ scaleContent: false,
      scaleX: false,
      restoreAfterFinish: true,
      afterFinishInternal: function(effect) {
        effect.element.hide().undoClipping();
      }
    }, arguments[1] || { })
  );
};

Effect.BlindDown = function(element) {
  element = $(element);
  var elementDimensions = element.getDimensions();
  return new Effect.Scale(element, 100, Object.extend({
    scaleContent: false,
    scaleX: false,
    scaleFrom: 0,
    scaleMode: {originalHeight: elementDimensions.height, originalWidth: elementDimensions.width},
    restoreAfterFinish: true,
    afterSetup: function(effect) {
      effect.element.makeClipping().setStyle({height: '0px'}).show();
    },
    afterFinishInternal: function(effect) {
      effect.element.undoClipping();
    }
  }, arguments[1] || { }));
};

Effect.SwitchOff = function(element) {
  element = $(element);
  var oldOpacity = element.getInlineOpacity();
  return new Effect.Appear(element, Object.extend({
    duration: 0.4,
    from: 0,
    transition: Effect.Transitions.flicker,
    afterFinishInternal: function(effect) {
      new Effect.Scale(effect.element, 1, {
        duration: 0.3, scaleFromCenter: true,
        scaleX: false, scaleContent: false, restoreAfterFinish: true,
        beforeSetup: function(effect) {
          effect.element.makePositioned().makeClipping();
        },
        afterFinishInternal: function(effect) {
          effect.element.hide().undoClipping().undoPositioned().setStyle({opacity: oldOpacity});
        }
      });
    }
  }, arguments[1] || { }));
};

Effect.DropOut = function(element) {
  element = $(element);
  var oldStyle = {
    top: element.getStyle('top'),
    left: element.getStyle('left'),
    opacity: element.getInlineOpacity() };
  return new Effect.Parallel(
    [ new Effect.Move(element, {x: 0, y: 100, sync: true }),
      new Effect.Opacity(element, { sync: true, to: 0.0 }) ],
    Object.extend(
      { duration: 0.5,
        beforeSetup: function(effect) {
          effect.effects[0].element.makePositioned();
        },
        afterFinishInternal: function(effect) {
          effect.effects[0].element.hide().undoPositioned().setStyle(oldStyle);
        }
      }, arguments[1] || { }));
};

Effect.Shake = function(element) {
  element = $(element);
  var options = Object.extend({
    distance: 20,
    duration: 0.5
  }, arguments[1] || {});
  var distance = parseFloat(options.distance);
  var split = parseFloat(options.duration) / 10.0;
  var oldStyle = {
    top: element.getStyle('top'),
    left: element.getStyle('left') };
    return new Effect.Move(element,
      { x:  distance, y: 0, duration: split, afterFinishInternal: function(effect) {
    new Effect.Move(effect.element,
      { x: -distance*2, y: 0, duration: split*2,  afterFinishInternal: function(effect) {
    new Effect.Move(effect.element,
      { x:  distance*2, y: 0, duration: split*2,  afterFinishInternal: function(effect) {
    new Effect.Move(effect.element,
      { x: -distance*2, y: 0, duration: split*2,  afterFinishInternal: function(effect) {
    new Effect.Move(effect.element,
      { x:  distance*2, y: 0, duration: split*2,  afterFinishInternal: function(effect) {
    new Effect.Move(effect.element,
      { x: -distance, y: 0, duration: split, afterFinishInternal: function(effect) {
        effect.element.undoPositioned().setStyle(oldStyle);
  }}); }}); }}); }}); }}); }});
};

Effect.SlideDown = function(element) {
  element = $(element).cleanWhitespace();
  // SlideDown need to have the content of the element wrapped in a container element with fixed height!
  var oldInnerBottom = element.down().getStyle('bottom');
  var elementDimensions = element.getDimensions();
  return new Effect.Scale(element, 100, Object.extend({
    scaleContent: false,
    scaleX: false,
    scaleFrom: window.opera ? 0 : 1,
    scaleMode: {originalHeight: elementDimensions.height, originalWidth: elementDimensions.width},
    restoreAfterFinish: true,
    afterSetup: function(effect) {
      effect.element.makePositioned();
      effect.element.down().makePositioned();
      if (window.opera) effect.element.setStyle({top: ''});
      effect.element.makeClipping().setStyle({height: '0px'}).show();
    },
    afterUpdateInternal: function(effect) {
      effect.element.down().setStyle({bottom:
        (effect.dims[0] - effect.element.clientHeight) + 'px' });
    },
    afterFinishInternal: function(effect) {
      effect.element.undoClipping().undoPositioned();
      effect.element.down().undoPositioned().setStyle({bottom: oldInnerBottom}); }
    }, arguments[1] || { })
  );
};

Effect.SlideUp = function(element) {
  element = $(element).cleanWhitespace();
  var oldInnerBottom = element.down().getStyle('bottom');
  var elementDimensions = element.getDimensions();
  return new Effect.Scale(element, window.opera ? 0 : 1,
   Object.extend({ scaleContent: false,
    scaleX: false,
    scaleMode: 'box',
    scaleFrom: 100,
    scaleMode: {originalHeight: elementDimensions.height, originalWidth: elementDimensions.width},
    restoreAfterFinish: true,
    afterSetup: function(effect) {
      effect.element.makePositioned();
      effect.element.down().makePositioned();
      if (window.opera) effect.element.setStyle({top: ''});
      effect.element.makeClipping().show();
    },
    afterUpdateInternal: function(effect) {
      effect.element.down().setStyle({bottom:
        (effect.dims[0] - effect.element.clientHeight) + 'px' });
    },
    afterFinishInternal: function(effect) {
      effect.element.hide().undoClipping().undoPositioned();
      effect.element.down().undoPositioned().setStyle({bottom: oldInnerBottom});
    }
   }, arguments[1] || { })
  );
};

// Bug in opera makes the TD containing this element expand for a instance after finish
Effect.Squish = function(element) {
  return new Effect.Scale(element, window.opera ? 1 : 0, {
    restoreAfterFinish: true,
    beforeSetup: function(effect) {
      effect.element.makeClipping();
    },
    afterFinishInternal: function(effect) {
      effect.element.hide().undoClipping();
    }
  });
};

Effect.Grow = function(element) {
  element = $(element);
  var options = Object.extend({
    direction: 'center',
    moveTransition: Effect.Transitions.sinoidal,
    scaleTransition: Effect.Transitions.sinoidal,
    opacityTransition: Effect.Transitions.full
  }, arguments[1] || { });
  var oldStyle = {
    top: element.style.top,
    left: element.style.left,
    height: element.style.height,
    width: element.style.width,
    opacity: element.getInlineOpacity() };

  var dims = element.getDimensions();
  var initialMoveX, initialMoveY;
  var moveX, moveY;

  switch (options.direction) {
    case 'top-left':
      initialMoveX = initialMoveY = moveX = moveY = 0;
      break;
    case 'top-right':
      initialMoveX = dims.width;
      initialMoveY = moveY = 0;
      moveX = -dims.width;
      break;
    case 'bottom-left':
      initialMoveX = moveX = 0;
      initialMoveY = dims.height;
      moveY = -dims.height;
      break;
    case 'bottom-right':
      initialMoveX = dims.width;
      initialMoveY = dims.height;
      moveX = -dims.width;
      moveY = -dims.height;
      break;
    case 'center':
      initialMoveX = dims.width / 2;
      initialMoveY = dims.height / 2;
      moveX = -dims.width / 2;
      moveY = -dims.height / 2;
      break;
  }

  return new Effect.Move(element, {
    x: initialMoveX,
    y: initialMoveY,
    duration: 0.01,
    beforeSetup: function(effect) {
      effect.element.hide().makeClipping().makePositioned();
    },
    afterFinishInternal: function(effect) {
      new Effect.Parallel(
        [ new Effect.Opacity(effect.element, { sync: true, to: 1.0, from: 0.0, transition: options.opacityTransition }),
          new Effect.Move(effect.element, { x: moveX, y: moveY, sync: true, transition: options.moveTransition }),
          new Effect.Scale(effect.element, 100, {
            scaleMode: { originalHeight: dims.height, originalWidth: dims.width },
            sync: true, scaleFrom: window.opera ? 1 : 0, transition: options.scaleTransition, restoreAfterFinish: true})
        ], Object.extend({
             beforeSetup: function(effect) {
               effect.effects[0].element.setStyle({height: '0px'}).show();
             },
             afterFinishInternal: function(effect) {
               effect.effects[0].element.undoClipping().undoPositioned().setStyle(oldStyle);
             }
           }, options)
      );
    }
  });
};

Effect.Shrink = function(element) {
  element = $(element);
  var options = Object.extend({
    direction: 'center',
    moveTransition: Effect.Transitions.sinoidal,
    scaleTransition: Effect.Transitions.sinoidal,
    opacityTransition: Effect.Transitions.none
  }, arguments[1] || { });
  var oldStyle = {
    top: element.style.top,
    left: element.style.left,
    height: element.style.height,
    width: element.style.width,
    opacity: element.getInlineOpacity() };

  var dims = element.getDimensions();
  var moveX, moveY;

  switch (options.direction) {
    case 'top-left':
      moveX = moveY = 0;
      break;
    case 'top-right':
      moveX = dims.width;
      moveY = 0;
      break;
    case 'bottom-left':
      moveX = 0;
      moveY = dims.height;
      break;
    case 'bottom-right':
      moveX = dims.width;
      moveY = dims.height;
      break;
    case 'center':
      moveX = dims.width / 2;
      moveY = dims.height / 2;
      break;
  }

  return new Effect.Parallel(
    [ new Effect.Opacity(element, { sync: true, to: 0.0, from: 1.0, transition: options.opacityTransition }),
      new Effect.Scale(element, window.opera ? 1 : 0, { sync: true, transition: options.scaleTransition, restoreAfterFinish: true}),
      new Effect.Move(element, { x: moveX, y: moveY, sync: true, transition: options.moveTransition })
    ], Object.extend({
         beforeStartInternal: function(effect) {
           effect.effects[0].element.makePositioned().makeClipping();
         },
         afterFinishInternal: function(effect) {
           effect.effects[0].element.hide().undoClipping().undoPositioned().setStyle(oldStyle); }
       }, options)
  );
};

Effect.Pulsate = function(element) {
  element = $(element);
  var options    = arguments[1] || { },
    oldOpacity = element.getInlineOpacity(),
    transition = options.transition || Effect.Transitions.linear,
    reverser   = function(pos){
      return 1 - transition((-Math.cos((pos*(options.pulses||5)*2)*Math.PI)/2) + .5);
    };

  return new Effect.Opacity(element,
    Object.extend(Object.extend({  duration: 2.0, from: 0,
      afterFinishInternal: function(effect) { effect.element.setStyle({opacity: oldOpacity}); }
    }, options), {transition: reverser}));
};

Effect.Fold = function(element) {
  element = $(element);
  var oldStyle = {
    top: element.style.top,
    left: element.style.left,
    width: element.style.width,
    height: element.style.height };
  element.makeClipping();
  return new Effect.Scale(element, 5, Object.extend({
    scaleContent: false,
    scaleX: false,
    afterFinishInternal: function(effect) {
    new Effect.Scale(element, 1, {
      scaleContent: false,
      scaleY: false,
      afterFinishInternal: function(effect) {
        effect.element.hide().undoClipping().setStyle(oldStyle);
      } });
  }}, arguments[1] || { }));
};

Effect.Morph = Class.create(Effect.Base, {
  initialize: function(element) {
    this.element = $(element);
    if (!this.element) throw(Effect._elementDoesNotExistError);
    var options = Object.extend({
      style: { }
    }, arguments[1] || { });

    if (!Object.isString(options.style)) this.style = $H(options.style);
    else {
      if (options.style.include(':'))
        this.style = options.style.parseStyle();
      else {
        this.element.addClassName(options.style);
        this.style = $H(this.element.getStyles());
        this.element.removeClassName(options.style);
        var css = this.element.getStyles();
        this.style = this.style.reject(function(style) {
          return style.value == css[style.key];
        });
        options.afterFinishInternal = function(effect) {
          effect.element.addClassName(effect.options.style);
          effect.transforms.each(function(transform) {
            effect.element.style[transform.style] = '';
          });
        };
      }
    }
    this.start(options);
  },

  setup: function(){
    function parseColor(color){
      if (!color || ['rgba(0, 0, 0, 0)','transparent'].include(color)) color = '#ffffff';
      color = color.parseColor();
      return $R(0,2).map(function(i){
        return parseInt( color.slice(i*2+1,i*2+3), 16 );
      });
    }
    this.transforms = this.style.map(function(pair){
      var property = pair[0], value = pair[1], unit = null;

      if (value.parseColor('#zzzzzz') != '#zzzzzz') {
        value = value.parseColor();
        unit  = 'color';
      } else if (property == 'opacity') {
        value = parseFloat(value);
        if (Prototype.Browser.IE && (!this.element.currentStyle.hasLayout))
          this.element.setStyle({zoom: 1});
      } else if (Element.CSS_LENGTH.test(value)) {
          var components = value.match(/^([\+\-]?[0-9\.]+)(.*)$/);
          value = parseFloat(components[1]);
          unit = (components.length == 3) ? components[2] : null;
      }

      var originalValue = this.element.getStyle(property);
      return {
        style: property.camelize(),
        originalValue: unit=='color' ? parseColor(originalValue) : parseFloat(originalValue || 0),
        targetValue: unit=='color' ? parseColor(value) : value,
        unit: unit
      };
    }.bind(this)).reject(function(transform){
      return (
        (transform.originalValue == transform.targetValue) ||
        (
          transform.unit != 'color' &&
          (isNaN(transform.originalValue) || isNaN(transform.targetValue))
        )
      );
    });
  },
  update: function(position) {
    var style = { }, transform, i = this.transforms.length;
    while(i--)
      style[(transform = this.transforms[i]).style] =
        transform.unit=='color' ? '#'+
          (Math.round(transform.originalValue[0]+
            (transform.targetValue[0]-transform.originalValue[0])*position)).toColorPart() +
          (Math.round(transform.originalValue[1]+
            (transform.targetValue[1]-transform.originalValue[1])*position)).toColorPart() +
          (Math.round(transform.originalValue[2]+
            (transform.targetValue[2]-transform.originalValue[2])*position)).toColorPart() :
        (transform.originalValue +
          (transform.targetValue - transform.originalValue) * position).toFixed(3) +
            (transform.unit === null ? '' : transform.unit);
    this.element.setStyle(style, true);
  }
});

Effect.Transform = Class.create({
  initialize: function(tracks){
    this.tracks  = [];
    this.options = arguments[1] || { };
    this.addTracks(tracks);
  },
  addTracks: function(tracks){
    tracks.each(function(track){
      track = $H(track);
      var data = track.values().first();
      this.tracks.push($H({
        ids:     track.keys().first(),
        effect:  Effect.Morph,
        options: { style: data }
      }));
    }.bind(this));
    return this;
  },
  play: function(){
    return new Effect.Parallel(
      this.tracks.map(function(track){
        var ids = track.get('ids'), effect = track.get('effect'), options = track.get('options');
        var elements = [$(ids) || $$(ids)].flatten();
        return elements.map(function(e){ return new effect(e, Object.extend({ sync:true }, options)) });
      }).flatten(),
      this.options
    );
  }
});

Element.CSS_PROPERTIES = $w(
  'backgroundColor backgroundPosition borderBottomColor borderBottomStyle ' +
  'borderBottomWidth borderLeftColor borderLeftStyle borderLeftWidth ' +
  'borderRightColor borderRightStyle borderRightWidth borderSpacing ' +
  'borderTopColor borderTopStyle borderTopWidth bottom clip color ' +
  'fontSize fontWeight height left letterSpacing lineHeight ' +
  'marginBottom marginLeft marginRight marginTop markerOffset maxHeight '+
  'maxWidth minHeight minWidth opacity outlineColor outlineOffset ' +
  'outlineWidth paddingBottom paddingLeft paddingRight paddingTop ' +
  'right textIndent top width wordSpacing zIndex');

Element.CSS_LENGTH = /^(([\+\-]?[0-9\.]+)(em|ex|px|in|cm|mm|pt|pc|\%))|0$/;

String.__parseStyleElement = document.createElement('div');
String.prototype.parseStyle = function(){
  var style, styleRules = $H();
  if (Prototype.Browser.WebKit)
    style = new Element('div',{style:this}).style;
  else {
    String.__parseStyleElement.innerHTML = '<div style="' + this + '"></div>';
    style = String.__parseStyleElement.childNodes[0].style;
  }

  Element.CSS_PROPERTIES.each(function(property){
    if (style[property]) styleRules.set(property, style[property]);
  });

  if (Prototype.Browser.IE && this.include('opacity'))
    styleRules.set('opacity', this.match(/opacity:\s*((?:0|1)?(?:\.\d*)?)/)[1]);

  return styleRules;
};

if (document.defaultView && document.defaultView.getComputedStyle) {
  Element.getStyles = function(element) {
    var css = document.defaultView.getComputedStyle($(element), null);
    return Element.CSS_PROPERTIES.inject({ }, function(styles, property) {
      styles[property] = css[property];
      return styles;
    });
  };
} else {
  Element.getStyles = function(element) {
    element = $(element);
    var css = element.currentStyle, styles;
    styles = Element.CSS_PROPERTIES.inject({ }, function(results, property) {
      results[property] = css[property];
      return results;
    });
    if (!styles.opacity) styles.opacity = element.getOpacity();
    return styles;
  };
}

Effect.Methods = {
  morph: function(element, style) {
    element = $(element);
    new Effect.Morph(element, Object.extend({ style: style }, arguments[2] || { }));
    return element;
  },
  visualEffect: function(element, effect, options) {
    element = $(element);
    var s = effect.dasherize().camelize(), klass = s.charAt(0).toUpperCase() + s.substring(1);
    new Effect[klass](element, options);
    return element;
  },
  highlight: function(element, options) {
    element = $(element);
    new Effect.Highlight(element, options);
    return element;
  }
};

$w('fade appear grow shrink fold blindUp blindDown slideUp slideDown '+
  'pulsate shake puff squish switchOff dropOut').each(
  function(effect) {
    Effect.Methods[effect] = function(element, options){
      element = $(element);
      Effect[effect.charAt(0).toUpperCase() + effect.substring(1)](element, options);
      return element;
    };
  }
);

$w('getInlineOpacity forceRerendering setContentZoom collectTextNodes collectTextNodesIgnoreClass getStyles').each(
  function(f) { Effect.Methods[f] = Element[f]; }
);

Element.addMethods(Effect.Methods);
// Copyright (c) 2005 Thomas Fuchs (http://script.aculo.us, http://mir.aculo.us)
//           (c) 2005 Sammi Williams (http://www.oriontransfer.co.nz, sammi@oriontransfer.co.nz)
//
// See scriptaculous.js for full license.

/* Sebastian Zaha, 18.01.07
 *   * modified Draggables so that the listeners for mousemove, mouseup and keyup are not active
 *     at all times
 *     * moved content from (un)register to _(un)observe
 *     * the said functions are called when activeDraggable is modified -> set to an existing element or to null.
 *
 * Sebastian Zaha, 06.03.07
 *   * added option to draggable so that for draggable nodes, we call our own findAffected method.
 *   * !!!! ATTENTION: if nodes will be able to be dragged on something else than other nodes and canvas, this
 *     has to be redone. Very hackish!
 */

/*--------------------------------------------------------------------------*/

if(typeof Effect == 'undefined')
    throw("dragdrop.js requires including script.aculo.us' effects.js library");

var Droppables = {
    drops: [],

    remove: function(element) {
        this.drops = this.drops.reject(function(d) { return d.element==$(element); });
    },

    add: function(element) {
        element = $(element);
        var options = Object.extend({
            greedy:     true,
            hoverclass: null,
            tree:       false,
            /* <sebi orig="mhollauf" for="mindmeister"> */
            isAffected: Droppables.isAffected
            /* </sebi> */
        }, arguments[1] || {});

        // cache containers
        if(options.containment) {
            options._containers = [];
            var containment = options.containment;
            if((typeof containment == 'object') &&
                (containment.constructor == Array)) {
                containment.each( function(c) { options._containers.push($(c)); });
            } else {
                options._containers.push($(containment));
            }
        }

        if(options.accept) options.accept = [options.accept].flatten();

        if (!options.positionLazy) // mindmeister mhollauf new option to make element positioned only on initDrag (for sortable Sidebar)
            Element.makePositioned(element); // fix IE
        options.element = element;

        this.drops.push(options);
    },

    findDeepestChild: function(drops) {
        deepest = drops[0];

        for (i = 1; i < drops.length; ++i)
            if (Element.isParent(drops[i].element, deepest.element))
                deepest = drops[i];

        return deepest;
    },

    isContained: function(element, drop) {
        var containmentNode;
        if(drop.tree) {
            containmentNode = element.treeNode;
        } else {
            containmentNode = element.parentNode;
        }
        return drop._containers.detect(function(c) { return containmentNode == c; });
    },

    isAffected: function(point, element, drop) {
        Position.prepare();
        return (
            (drop.element!=element) &&
                ((!drop._containers) ||
                    this.isContained(element, drop)) &&
                ((!drop.accept) ||
                    (Element.classNames(element).detect(
                        function(v) { return drop.accept.include(v); } ) )) &&
                Position.withinIncludingScrolloffsets(drop.element, point[0], point[1]) ); // mhollauf mindmeister: fix for scrolling offset errors, see http://bit.ly/aIQL2i
    },


    deactivate: function(drop) {
        if(drop.hoverclass)
            Element.removeClassName(drop.element, drop.hoverclass);
        this.last_active = null;
    },

    activate: function(drop) {
        if(drop.hoverclass)
            Element.addClassName(drop.element, drop.hoverclass);
        this.last_active = drop;
    },

    show: function(point, element, customFindAffected) {
        if(!this.drops.length) return;
        var affected = [], affectedElement;

        if(this.last_active) this.deactivate(this.last_active);

        if (customFindAffected) {
            if (affectedElement = customFindAffected(point)) {
                affected.push(this.drops.detect(function(el) { return el.element == affectedElement; } ));
            }
        } else {
            this.drops.each( function(drop) {
                if (Droppables.isAffected(point, element, drop)) {
                    affected.push(drop);
                    if (drop.greedy) throw $break;
                }
            });
        }

        if(affected.length>0) {
            drop = Droppables.findDeepestChild(affected);
            Position.withinIncludingScrolloffsets(drop.element, point[0], point[1]); // mhollauf mindmeister: fix for scrolling offset errors, see http://bit.ly/aIQL2i
            if(drop.onHover)
                drop.onHover(element, drop.element, Position.overlap(drop.overlap, drop.element));

            Droppables.activate(drop);
        }
    },

    fire: function(event, element) {
        if(!this.last_active) return;
        Position.prepare();

        if (this.isAffected([Event.pointerX(event), Event.pointerY(event)], element, this.last_active))
            if (this.last_active.onDrop) {
                this.last_active.onDrop(element, this.last_active.element, event);
                return true;
            }
    },

    reset: function() {
        if(this.last_active)
            this.deactivate(this.last_active);
    }
};

var Draggables = {
    drags: [],
    observers: [],

    register: function(draggable) {
        if(this.drags.length == 0) {
            this.eventMouseUp   = this.endDrag.bindAsEventListener(this);
            this.eventMouseMove = this.updateDrag.bindAsEventListener(this);
            this.eventKeypress  = this.keyPress.bindAsEventListener(this);
        }
        this.drags.push(draggable);
    },

    unregister: function(draggable) {
        this.drags = this.drags.reject(function(d) { return d == draggable; });
    },

    _observe : function() {
        document.observe("mouseup", this.eventMouseUp);
        document.observe("mousemove", this.eventMouseMove);
        document.observe("keypress", this.eventKeypress);
    },

    _unobserve : function() {
        document.stopObserving("mouseup", this.eventMouseUp);
        document.stopObserving("mousemove", this.eventMouseMove);
        document.stopObserving("keypress", this.eventKeypress);
    },

    activate: function(draggable) {
        if(draggable.options.delay) {
            this._timeout = setTimeout(function() {
                Draggables._timeout = null;
                window.focus();
                Draggables.activeDraggable = draggable;
            }.bind(this), draggable.options.delay);
        } else {
            window.focus(); // allows keypress events if window isn't currently focused, fails for Safari
            this.activeDraggable = draggable;
        }
        //this._startoffset = Element.cumulativeScrollOffset(this.activeDraggable.element);
        this._observe();
    },

    deactivate: function() {
        this.activeDraggable = null;
        this._unobserve();
    },

    updateDrag: function(event) {
        if(!this.activeDraggable) return;
        var pointer = [Event.pointerX(event), Event.pointerY(event)];

        // mhollauf mindmeister: fix for scrolling offset errors, see http://bit.ly/aIQL2i (and below) - doesnt work
        /* var offsetcache = Element.cumulativeScrollOffset(this.activeDraggable.element);
         pointer[0] += offsetcache[0] - this._startoffset[0];
         pointer[1] += offsetcache[1] - this._startoffset[1];
         */

        // Mozilla-based browsers fire successive mousemove events with
        // the same coordinates, prevent needless redrawing (moz bug?)
        if(this._lastPointer && (this._lastPointer.inspect() == pointer.inspect())) return;
        this._lastPointer = pointer;

        this.activeDraggable.updateDrag(event, pointer);
    },

    endDrag: function(event) {
        if(this._timeout) {
            clearTimeout(this._timeout);
            this._timeout = null;
        }
        if(!this.activeDraggable) return;
        this._lastPointer = null;
        this.activeDraggable.endDrag(event);
        this.activeDraggable = null;
        this._unobserve();
    },

    keyPress: function(event) {
        if(this.activeDraggable)
            this.activeDraggable.keyPress(event);
    },

    addObserver: function(observer) {
        this.observers.push(observer);
        this._cacheObserverCallbacks();
    },

    removeObserver: function(element) {  // element instead of observer fixes mem leaks
        this.observers = this.observers.reject( function(o) { return o.element == element; });
        this._cacheObserverCallbacks();
    },

    notify: function(eventName, draggable, event) {  // 'onStart', 'onEnd', 'onDrag'
        if(this[eventName+'Count'] > 0)
            this.observers.each( function(o) {
                if(o[eventName]) o[eventName](eventName, draggable, event);
            });
        if(draggable.options[eventName]) draggable.options[eventName](draggable, event);
    },

    _cacheObserverCallbacks: function() {
        ['onStart','onEnd','onDrag'].each( function(eventName) {
            Draggables[eventName+'Count'] = Draggables.observers.select(
                function(o) { return o[eventName]; }
                ).length;
        });
    }
};

/*--------------------------------------------------------------------------*/

var Draggable = Class.create();
Draggable._dragging    = {};
Draggable._revertCache = {};

Draggable.prototype = {
    initialize: function(element) {
        var defaults = {
            handle: false,
            reverteffect: function(element, top_offset, left_offset) {
                var dur = Math.sqrt(Math.abs(top_offset^2) + Math.abs(left_offset^2)) * 0.02;
                Draggable._revertCache[element] =
                    new Effect.Move(element, { x: -left_offset, y: -top_offset, duration: dur,
                        queue: {scope:'_draggable', position:'end'}
                    });
            },
            endeffect: function(element) {
                var toOpacity = typeof element._opacity == 'number' ? element._opacity : 1.0;
                new Effect.Opacity(element, {duration:0.2, from:0.7, to:toOpacity,
                    queue: {scope:'_draggable', position:'end'},
                    afterFinish: function(){
                        Draggable._dragging[element] = false;
                    }
                });
            },
            zindex: 1000,
            revert: false,
            quiet: false,
            scroll: false,
            scrollSensitivity: 20,
            scrollSpeed: 15,
            snap: false,  // false, or xy or [x,y] or function(x,y){ return [x,y] }
            delay: 0
        };

        if(!arguments[1] || typeof arguments[1].endeffect == 'undefined')
            Object.extend(defaults, {
                starteffect: function(element) {
                    element._opacity = Element.getOpacity(element);
                    Draggable._dragging[element] = true;
                    new Effect.Opacity(element, {duration:0.2, from:element._opacity, to:0.7});
                }
            });

        var options = Object.extend(defaults, arguments[1] || {});

        this.element = $(element);

        if(options.handle && (typeof options.handle == 'string'))
            this.handle = this.element.down('.' + options.handle, 0);

        if(!this.handle) this.handle = $(options.handle);
        if(!this.handle) this.handle = this.element;

        if(options.scroll && !options.scroll.scrollTo && !options.scroll.outerHTML) {
            options.scroll = $(options.scroll);
            this._isScrollChild = Element.childOf(this.element, options.scroll);
        }

        /* <mhollauf for="mindmeister"> */
        /* new option to make element positioned only on initDrag (for sortable Sidebar) */
        if (!options.positionLazy)
            Element.makePositioned(this.element); // fix IE
        /* </mhollauf> */

        this.delta = this.currentDelta();
        this.options = options;
        this.dragging = false;

        this.eventMouseDown = this.initDrag.bindAsEventListener(this);
        this.handle.observe("mousedown", this.eventMouseDown);
        Draggables.register(this);
    },

    disableDragging: function() {
        this.handle.stopObserving("mousedown", this.eventMouseDown);
    },

    enableDragging: function() {
        this.handle.observe("mousedown", this.eventMouseDown);
    },

    destroy: function() {
        this.handle.stopObserving("mousedown", this.eventMouseDown);
        Draggables.unregister(this);
    },

    currentDelta: function() {
        return([parseInt(Element.getStyle(this.element,'left') || '0'),
            parseInt(Element.getStyle(this.element,'top') || '0')]);
    },

    initDrag: function(event) {
        if (typeof Draggable._dragging[this.element] != 'undefined' && Draggable._dragging[this.element]) return;

        if (Event.isLeftClick(event)) {
            // abort on form elements, fixes a Firefox issue
            var src = Event.element(event);
            if ((tag_name = src.tagName.toUpperCase()) && (tag_name === 'INPUT' || tag_name === 'SELECT' ||
                tag_name === 'OPTION' || tag_name === 'BUTTON' || tag_name === 'TEXTAREA')) return;

            if (Draggable._revertCache[this.element]) {
                Draggable._revertCache[this.element].cancel();
                Draggable._revertCache[this.element] = null;
            }

            var pointer = [Event.pointerX(event), Event.pointerY(event)];
            var pos = Position.cumulativeOffset(this.element);
            var offset = [];
            if (this.options.customClone) {
                offset[0] = 0; offset[1] = 0;
            } else {
                offset[0] = pointer[0] - pos[0];
                offset[1] = pointer[1] - pos[1];
            }
            this.offset = offset;

            /* <sebi for="mindmeister"> */
            this.element.offset = this.offset;
            /* </sebi> */

            Draggables.activate(this);
            Event.stop(event);
        }
    },

    startDrag: function(event) {
        this.dragging = true;

        if (this.options.zindex) {
            this.originalZ = parseInt(Element.getStyle(this.element, 'z-index') || 0);
            this.element.style.zIndex = this.options.zindex;
        }

        if (this.options.ghosting) {
            this._clone = this.element.cloneNode(true);
            /* <mhollauf for="mindmeister"> */
            if (this.element._listener)
                this._clone.style.color = "#ccc";
            /* </mhollauf> */
            Position.absolutize(this.element);
            this.element.parentNode.insertBefore(this._clone, this.element);
        }

        if (this.options.scroll) {
            if (this.options.scroll == window) {
                var where = this._getWindowScroll(this.options.scroll);
                this.originalScrollLeft = where.left;
                this.originalScrollTop = where.top;
            } else {
                this.originalScrollLeft = this.options.scroll.scrollLeft;
                this.originalScrollTop = this.options.scroll.scrollTop;
            }
        }

        Draggables.notify('onStart', this, event);
        if (this.options.starteffect) this.options.starteffect(this.element);
    },

    updateDrag: function(event, pointer) {
        if (!this.dragging) this.startDrag(event);

        if (!this.options.quiet){
            Position.prepare();
            Droppables.show(pointer, this.element, this.options.customFindAffected);
        }

        Draggables.notify('onDrag', this, event);

        this.draw(pointer);
        if (this.options.change) this.options.change(this);

        if (this.options.scroll) {
            this.stopScrolling();

            var p;
            if (this.options.scroll === window) {
                with (this._getWindowScroll(this.options.scroll)) { p = [ left, top, left + width, top + height ]; }
            } else {
                p = Position.page(this.options.scroll);
                p[0] += this.options.scroll.scrollLeft + Position.deltaX;
                p[1] += this.options.scroll.scrollTop + Position.deltaY;
                p.push(p[0] + this.options.scroll.offsetWidth);
                p.push(p[1] + this.options.scroll.offsetHeight);
            }
            var speed = [0,0];
            if (pointer[0] < (p[0] + this.options.scrollSensitivity)) speed[0] = pointer[0] - (p[0] + this.options.scrollSensitivity);
            if (pointer[1] < (p[1] + this.options.scrollSensitivity)) speed[1] = pointer[1] - (p[1] + this.options.scrollSensitivity);
            if (pointer[0] > (p[2] - this.options.scrollSensitivity)) speed[0] = pointer[0] - (p[2] - this.options.scrollSensitivity);
            if (pointer[1] > (p[3] - this.options.scrollSensitivity)) speed[1] = pointer[1] - (p[3] - this.options.scrollSensitivity);
            this.startScrolling(speed);
        }

        // fix AppleWebKit rendering
        if(Prototype.Browser.WebKit) window.scrollBy(0,0);
        Event.stop(event);
    },

    finishDrag: function(event, success) {
        this.dragging = false;

        if (this.options.quiet){
            Position.prepare();
            var pointer = [Event.pointerX(event), Event.pointerY(event)];
            Droppables.show(pointer, this.element);
        }

        if (this.options.ghosting) {
            /* <mhollauf for="mindmeister"> */
            if (!this.element._listener)
                Position.relativize(this.element);
            /* </mhollauf> */
            Element.remove(this._clone);
            this._clone = null;
        }

        var dropped = false;
        if (success) {
            dropped = Droppables.fire(event, this.element);
            if (!dropped) dropped = false;
        }
        if(dropped && this.options.onDropped) this.options.onDropped(this.element);
        Draggables.notify('onEnd', this, event);

        var revert = this.options.revert;
        if (revert && typeof revert == 'function') revert = revert(this.element);

        var d = this.currentDelta();
        if(revert && this.options.reverteffect) {
            if (dropped == 0 || revert != 'failure')
                this.options.reverteffect(this.element, d[1] - this.delta[1], d[0] - this.delta[0]);
        } else {
            this.delta = d;
        }

        /* <mhollauf for="mindmeister"> */
        /* don't set zindex if it wasnt set before, messes up popup panels in sidebar after sorting widgets */
        if (this.options.zindex && this.originalZ != 0)
            this.element.style.zIndex = this.originalZ;
        /* </mhollauf> */

        if(this.options.endeffect)
            this.options.endeffect(this.element);

        Draggables.deactivate(this);
        Droppables.reset();
    },

    keyPress: function(event) {
        if (event.keyCode != Event.KEY_ESC) return;
        this.finishDrag(event, false);
        Event.stop(event);
    },

    endDrag: function(event) {
        if (!this.dragging) return;
        this.stopScrolling();
        this.finishDrag(event, true);
        Event.stop(event);
    },

    draw: function(point) {
        var pos = Position.cumulativeOffset(this.element);
        var d = this.currentDelta();
        pos[0] -= d[0]; pos[1] -= d[1];

        if (this.options.scroll && (this.options.scroll != window && this._isScrollChild)) {
            pos[0] -= this.options.scroll.scrollLeft - this.originalScrollLeft;
            pos[1] -= this.options.scroll.scrollTop - this.originalScrollTop;
        }

        /* <mhollauf for="mindmeister"> */
        /* so drags in share dialog are positioned properly when scrolled down */
        if (this.options.scroll && this.options.scroll.id == "share_friends") {
            pos[1] += this.options.scroll.scrollTop;
        }
        /* </mhollauf */

        var p = [0,1].map(function(i) { return (point[i] - pos[i] - this.offset[i]); }.bind(this));

        if (this.options.snap) {
            if(typeof this.options.snap == 'function') {
                p = this.options.snap(p[0],p[1],this);
            } else {
                if(this.options.snap instanceof Array) {
                    p = p.map( function(v, i) { return Math.round(v/this.options.snap[i])*this.options.snap[i]; }.bind(this));
                } else {
                    p = p.map( function(v) { return Math.round(v/this.options.snap)*this.options.snap; }.bind(this));
                }
            }}

        var style = this.element.style;
        if ((!this.options.constraint) || (this.options.constraint == 'horizontal'))
            style.left = p[0] + "px";
        if ((!this.options.constraint) || (this.options.constraint == 'vertical'))
            style.top  = p[1] + "px";

        if (style.visibility == "hidden") style.visibility = ""; // fix gecko rendering
    },

    stopScrolling: function() {
        if (this.scrollInterval) {
            clearInterval(this.scrollInterval);
            this.scrollInterval = null;
            Draggables._lastScrollPointer = null;
        }
    },

    startScrolling: function(speed) {
        if (!(speed[0] || speed[1])) return;
        this.scrollSpeed = [speed[0]*this.options.scrollSpeed,speed[1]*this.options.scrollSpeed];
        this.lastScrolled = new Date();
        this.scrollInterval = setInterval(this.scroll.bind(this), 10);
    },

    scroll: function() {
        var current = new Date();
        var delta = current - this.lastScrolled;
        this.lastScrolled = current;
        if(this.options.scroll == window) {
            with (this._getWindowScroll(this.options.scroll)) {
                if (this.scrollSpeed[0] || this.scrollSpeed[1]) {
                    var d = delta / 1000;
                    this.options.scroll.scrollTo( left + d*this.scrollSpeed[0], top + d*this.scrollSpeed[1] );
                }
            }
        } else {
            this.options.scroll.scrollLeft += this.scrollSpeed[0] * delta / 1000;
            this.options.scroll.scrollTop  += this.scrollSpeed[1] * delta / 1000;
        }

        Position.prepare();
        Droppables.show(Draggables._lastPointer, this.element);
        Draggables.notify('onDrag', this);
        if (this._isScrollChild) {
            Draggables._lastScrollPointer = Draggables._lastScrollPointer || $A(Draggables._lastPointer);
            Draggables._lastScrollPointer[0] += this.scrollSpeed[0] * delta / 1000;
            Draggables._lastScrollPointer[1] += this.scrollSpeed[1] * delta / 1000;
            if (Draggables._lastScrollPointer[0] < 0)
                Draggables._lastScrollPointer[0] = 0;
            if (Draggables._lastScrollPointer[1] < 0)
                Draggables._lastScrollPointer[1] = 0;
            this.draw(Draggables._lastScrollPointer);
        }

        if(this.options.change) this.options.change(this);
    },

    _getWindowScroll: function(w) {
        var T, L, W, H;
        with (w.document) {
            if (w.document.documentElement && documentElement.scrollTop) {
                T = documentElement.scrollTop;
                L = documentElement.scrollLeft;
            } else if (w.document.body) {
                T = body.scrollTop;
                L = body.scrollLeft;
            }
            if (w.innerWidth) {
                W = w.innerWidth;
                H = w.innerHeight;
            } else if (w.document.documentElement && documentElement.clientWidth) {
                W = documentElement.clientWidth;
                H = documentElement.clientHeight;
            } else {
                W = body.offsetWidth;
                H = body.offsetHeight;
            }
        }
        return { top: T, left: L, width: W, height: H };
    }
};

/*--------------------------------------------------------------------------*/

var SortableObserver = Class.create();
SortableObserver.prototype = {
    initialize: function(element, observer) {
        this.element   = $(element);
        this.observer  = observer;
        this.lastValue = Sortable.serialize(this.element);
    },

    onStart: function() {
        this.lastValue = Sortable.serialize(this.element);
    },

    onEnd: function() {
        Sortable.unmark();
        if(this.lastValue != Sortable.serialize(this.element))
            this.observer(this.element);
    }
};

var Sortable = {
    SERIALIZE_RULE: /^[^_\-](?:[A-Za-z0-9\-\_]*)[_](.*)$/,

    sortables: {},

    _findRootElement: function(element) {
        while (element.tagName.toUpperCase() != "BODY") {
            if(element.id && Sortable.sortables[element.id]) return element;
            element = element.parentNode;
        }
    },

    options: function(element) {
        element = Sortable._findRootElement($(element));
        if(!element) return;
        return Sortable.sortables[element.id];
    },

    destroy: function(element){
        var s = Sortable.options(element);

        if(s) {
            Draggables.removeObserver(s.element);
            s.droppables.each(function(d){ Droppables.remove(d); });
            s.draggables.invoke('destroy');

            delete Sortable.sortables[s.element.id];
        }
    },

    create: function(element) {
        element = $(element);
        var options = Object.extend({
            element:     element,
            tag:         'li',       // assumes li children, override with tag: 'tagname'
            dropOnEmpty: false,
            tree:        false,
            treeTag:     'ul',
            overlap:     'vertical', // one of 'vertical', 'horizontal'
            constraint:  'vertical', // one of 'vertical', 'horizontal', false
            containment: element,    // also takes array of elements (or id's); or false
            handle:      false,      // or a CSS class
            only:        false,
            delay:       0,
            hoverclass:  null,
            ghosting:    false,
            quiet:       false,
            scroll:      false,
            scrollSensitivity: 20,
            scrollSpeed: 15,
            format:      this.SERIALIZE_RULE,
            onChange:    Prototype.emptyFunction,
            onUpdate:    Prototype.emptyFunction
        }, arguments[1] || {});

        // clear any old sortable with same element
        this.destroy(element);

        // build options for the draggables
        var options_for_draggable = {
            revert:      true,
            quiet:       options.quiet,
            scroll:      options.scroll,
            scrollSpeed: options.scrollSpeed,
            scrollSensitivity: options.scrollSensitivity,
            delay:       options.delay,
            ghosting:    options.ghosting,
            constraint:  options.constraint,
            positionLazy: options.positionLazy, // mindmeister mhollauf
            handle:      options.handle };

        if(options.starteffect)
            options_for_draggable.starteffect = options.starteffect;

        if(options.reverteffect)
            options_for_draggable.reverteffect = options.reverteffect;
        else
        if(options.ghosting) options_for_draggable.reverteffect = function(element) {
            element.style.top  = 0;
            element.style.left = 0;
        };

        if(options.endeffect)
            options_for_draggable.endeffect = options.endeffect;

        if(options.zindex)
            options_for_draggable.zindex = options.zindex;

        // build options for the droppables
        var options_for_droppable = {
            overlap:     options.overlap,
            containment: options.containment,
            tree:        options.tree,
            hoverclass:  options.hoverclass,
            positionLazy: options.positionLazy, // mindmeister mhollauf
            onHover:     Sortable.onHover
        };

        var options_for_tree = {
            onHover:      Sortable.onEmptyHover,
            overlap:      options.overlap,
            containment:  options.containment,
            hoverclass:   options.hoverclass
        };

        // fix for gecko engine
        Element.cleanWhitespace(element);

        options.draggables = [];
        options.droppables = [];

        // drop on empty handling
        if(options.dropOnEmpty || options.tree) {
            Droppables.add(element, options_for_tree);
            options.droppables.push(element);
        }

        (this.findElements(element, options) || []).each( function(e) {
            // handles are per-draggable
            var handle = options.handle ?
                $(e).down('.'+options.handle,0) : e;
            options.draggables.push(
                new Draggable(e, Object.extend(options_for_draggable, { handle: handle })));
            Droppables.add(e, options_for_droppable);
            if(options.tree) e.treeNode = element;
            options.droppables.push(e);
        });

        if(options.tree) {
            (Sortable.findTreeElements(element, options) || []).each( function(e) {
                Droppables.add(e, options_for_tree);
                e.treeNode = element;
                options.droppables.push(e);
            });
        }

        // keep reference
        this.sortables[element.id] = options;

        // for onupdate
        Draggables.addObserver(new SortableObserver(element, options.onUpdate));

    },

    // return all suitable-for-sortable elements in a guaranteed order
    findElements: function(element, options) {
        return Element.findChildren(
            element, options.only, options.tree ? true : false, options.tag);
    },

    findTreeElements: function(element, options) {
        return Element.findChildren(
            element, options.only, options.tree ? true : false, options.treeTag);
    },

    onHover: function(element, dropon, overlap) {
        if(Element.isParent(dropon, element)) return;

        if(overlap > .33 && overlap < .66 && Sortable.options(dropon).tree) {
            return;
        } else if(overlap>0.5) {
            Sortable.mark(dropon, 'before');
            if(dropon.previousSibling != element) {
                var oldParentNode = element.parentNode;
                element.style.visibility = "hidden"; // fix gecko rendering
                dropon.parentNode.insertBefore(element, dropon);
                if(dropon.parentNode!=oldParentNode)
                    Sortable.options(oldParentNode).onChange(element);
                Sortable.options(dropon.parentNode).onChange(element);
            }
        } else {
            Sortable.mark(dropon, 'after');
            var nextElement = dropon.nextSibling || null;
            if(nextElement != element) {
                var oldParentNode = element.parentNode;
                element.style.visibility = "hidden"; // fix gecko rendering
                dropon.parentNode.insertBefore(element, nextElement);
                if(dropon.parentNode!=oldParentNode)
                    Sortable.options(oldParentNode).onChange(element);
                Sortable.options(dropon.parentNode).onChange(element);
            }
        }
    },

    onEmptyHover: function(element, dropon, overlap) {
        var oldParentNode = element.parentNode;
        var droponOptions = Sortable.options(dropon);

        if(!Element.isParent(dropon, element)) {
            var index;

            var children = Sortable.findElements(dropon, {tag: droponOptions.tag, only: droponOptions.only});
            var child = null;

            if(children) {
                var offset = Element.offsetSize(dropon, droponOptions.overlap) * (1.0 - overlap);

                for (index = 0; index < children.length; index += 1) {
                    if (offset - Element.offsetSize (children[index], droponOptions.overlap) >= 0) {
                        offset -= Element.offsetSize (children[index], droponOptions.overlap);
                    } else if (offset - (Element.offsetSize (children[index], droponOptions.overlap) / 2) >= 0) {
                        child = index + 1 < children.length ? children[index + 1] : null;
                        break;
                    } else {
                        child = children[index];
                        break;
                    }
                }
            }

            dropon.insertBefore(element, child);

            Sortable.options(oldParentNode).onChange(element);
            droponOptions.onChange(element);
        }
    },

    unmark: function() {
        if(Sortable._marker) Sortable._marker.hide();
    },

    mark: function(dropon, position) {
        // mark on ghosting only
        var sortable = Sortable.options(dropon.parentNode);
        if(sortable && !sortable.ghosting) return;

        if(!Sortable._marker) {
            Sortable._marker =
                ($('dropmarker') || Element.extend(document.createElement('DIV'))).
                    hide().addClassName('dropmarker').setStyle({position:'absolute'});
            document.getElementsByTagName("body").item(0).appendChild(Sortable._marker);
        }
        var offsets = Position.cumulativeOffset(dropon);
        Sortable._marker.setStyle({left: offsets[0]+'px', top: offsets[1] + 'px'});

        if(position=='after')
            if(sortable.overlap == 'horizontal')
                Sortable._marker.setStyle({left: (offsets[0]+dropon.clientWidth) + 'px'});
            else
                Sortable._marker.setStyle({top: (offsets[1]+dropon.clientHeight) + 'px'});

        Sortable._marker.show();
    },

    _tree: function(element, options, parent) {
        var children = Sortable.findElements(element, options) || [];

        for (var i = 0; i < children.length; ++i) {
            var match = children[i].id.match(options.format);

            if (!match) continue;

            var child = {
                id: encodeURIComponent(match ? match[1] : null),
                element: element,
                parent: parent,
                children: [],
                position: parent.children.length,
                container: $(children[i]).down(options.treeTag)
            };

            /* Get the element containing the children and recurse over it */
            if (child.container)
                this._tree(child.container, options, child);

            parent.children.push (child);
        }

        return parent;
    },

    tree: function(element) {
        element = $(element);
        var sortableOptions = this.options(element);
        var options = Object.extend({
            tag: sortableOptions.tag,
            treeTag: sortableOptions.treeTag,
            only: sortableOptions.only,
            name: element.id,
            format: sortableOptions.format
        }, arguments[1] || {});

        var root = {
            id: null,
            parent: null,
            children: [],
            container: element,
            position: 0
        };

        return Sortable._tree(element, options, root);
    },

    /* Construct a [i] index for a particular node */
    _constructIndex: function(node) {
        var index = '';
        do {
            if (node.id) index = '[' + node.position + ']' + index;
        } while ((node = node.parent) != null);
        return index;
    },

    sequence: function(element) {
        element = $(element);
        var options = Object.extend(this.options(element), arguments[1] || {});

        return $(this.findElements(element, options) || []).map( function(item) {
            return item.id.match(options.format) ? item.id.match(options.format)[1] : '';
        });
    },

    setSequence: function(element, new_sequence) {
        element = $(element);
        var options = Object.extend(this.options(element), arguments[2] || {});

        var nodeMap = {};
        this.findElements(element, options).each( function(n) {
            if (n.id.match(options.format))
                nodeMap[n.id.match(options.format)[1]] = [n, n.parentNode];
            n.parentNode.removeChild(n);
        });

        new_sequence.each(function(ident) {
            var n = nodeMap[ident];
            if (n) {
                n[1].appendChild(n[0]);
                delete nodeMap[ident];
            }
        });
    },

    serialize: function(element) {
        element = $(element);
        var options = Object.extend(Sortable.options(element), arguments[1] || {});
        var name = encodeURIComponent(
            (arguments[1] && arguments[1].name) ? arguments[1].name : element.id);

        if (options.tree) {
            return Sortable.tree(element, arguments[1]).children.map( function (item) {
                return [name + Sortable._constructIndex(item) + "[id]=" +
                    encodeURIComponent(item.id)].concat(item.children.map(arguments.callee));
            }).flatten().join('&');
        } else {
            return Sortable.sequence(element, arguments[1]).map( function(item) {
                return name + "[]=" + encodeURIComponent(item);
            }).join('&');
        }
    }
};

// Returns true if child is contained within element
Element.isParent = function(child, element) {
    if (!child.parentNode || child == element) return false;
    if (child.parentNode == element) return true;
    return Element.isParent(child.parentNode, element);
};

Element.findChildren = function(element, only, recursive, tagName) {
    if(!element.hasChildNodes()) return null;
    tagName = tagName.toUpperCase();
    if(only) only = [only].flatten();
    var elements = [];
    $A(element.childNodes).each( function(e) {
        if(e.tagName && e.tagName.toUpperCase()==tagName &&
            (!only || (Element.classNames(e).detect(function(v) { return only.include(v); }))))
            elements.push(e);
        if(recursive) {
            var grandchildren = Element.findChildren(e, only, recursive, tagName);
            if(grandchildren) elements.push(grandchildren);
        }
    });

    return (elements.length>0 ? elements.flatten() : []);
};

Element.offsetSize = function (element, type) {
    return element['offset' + ((type=='vertical' || type=='height') ? 'Height' : 'Width')];
};
// script.aculo.us controls.js v1.7.1_beta1, Mon Mar 12 14:40:50 +0100 2007

// Copyright (c) 2005-2007 Thomas Fuchs (http://script.aculo.us, http://mir.aculo.us)
//           (c) 2005-2007 Ivan Krstic (http://blogs.law.harvard.edu/ivan)
//           (c) 2005-2007 Jon Tirsen (http://www.tirsen.com)
// Contributors:
//  Richard Livsey
//  Rahul Bhargava
//  Rob Wills
// 
// script.aculo.us is freely distributable under the terms of an MIT-style license.
// For details, see the script.aculo.us web site: http://script.aculo.us/

// Autocompleter.Base handles all the autocompletion functionality 
// that's independent of the data source for autocompletion. This
// includes drawing the autocompletion menu, observing keyboard
// and mouse events, and similar.
//
// Specific autocompleters need to provide, at the very least, 
// a getUpdatedChoices function that will be invoked every time
// the text inside the monitored textbox changes. This method 
// should get the text for which to provide autocompletion by
// invoking this.getToken(), NOT by directly accessing
// this.element.value. This is to allow incremental tokenized
// autocompletion. Specific auto-completion logic (AJAX, etc)
// belongs in getUpdatedChoices.
//
// Tokenized incremental autocompletion is enabled automatically
// when an autocompleter is instantiated with the 'tokens' option
// in the options parameter, e.g.:
// new Ajax.Autocompleter('id','upd', '/url/', { tokens: ',' });
// will incrementally autocomplete with a comma as the token.
// Additionally, ',' in the above example can be replaced with
// a token array, e.g. { tokens: [',', '\n'] } which
// enables autocompletion on multiple tokens. This is most 
// useful when one of the tokens is \n (a newline), as it 
// allows smart autocompletion after linebreaks.

if(typeof Effect == 'undefined')
  throw("controls.js requires including script.aculo.us' effects.js library");

var Autocompleter = {};
Autocompleter.Base = function() {};
Autocompleter.Base.prototype = {
  baseInitialize: function(element, update, options) {
    this.element     = $(element); 
    this.update      = $(update);  
    this.hasFocus    = false; 
    this.changed     = false; 
    this.active      = false; 
    this.index       = 0;     
    this.entryCount  = 0;

    if(this.setOptions)
      this.setOptions(options);
    else
      this.options = options || {};

    this.options.paramName    = this.options.paramName || this.element.name;
    this.options.tokens       = this.options.tokens || [];
    this.options.frequency    = this.options.frequency || 0.4;
    this.options.minChars     = this.options.minChars || 1;
    this.options.onShow       = this.options.onShow || 
      function(element, update){ 
        if(!update.style.position || update.style.position=='absolute') {
          update.style.position = 'absolute';
          Position.clone(element, update, {
            setHeight: false, 
            offsetTop: element.offsetHeight
          });
        }
        Effect.Appear(update,{duration:0.15});
      };
    this.options.onHide = this.options.onHide || 
      function(element, update){ new Effect.Fade(update,{duration:0.15}); };

    if(typeof(this.options.tokens) == 'string') 
      this.options.tokens = new Array(this.options.tokens);

    this.observer = null;
    
    this.element.setAttribute('autocomplete','off');

    Element.hide(this.update);

    Event.observe(this.element, "blur", this.onBlur.bindAsEventListener(this));
    Event.observe(this.element, "keypress", this.onKeyPress.bindAsEventListener(this));
  },

  show: function() {
    if(Element.getStyle(this.update, 'display')=='none') this.options.onShow(this.element, this.update);
    if(!this.iefix && 
      (Prototype.Browser.IE) &&
      (Element.getStyle(this.update, 'position')=='absolute')) {
      new Insertion.After(this.update, 
       '<iframe id="' + this.update.id + '_iefix" '+
       'style="display:none;position:absolute;filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);" ' +
       'src="javascript:false;" frameborder="0" scrolling="no"></iframe>');
      this.iefix = $(this.update.id+'_iefix');
    }
    if(this.iefix) setTimeout(this.fixIEOverlapping.bind(this), 50);
  },
  
  fixIEOverlapping: function() {
    Position.clone(this.update, this.iefix, {setTop:(!this.update.style.height)});
    this.iefix.style.zIndex = 1;
    this.update.style.zIndex = 2;
    Element.show(this.iefix);
  },

  hide: function() {
    this.stopIndicator();
    if(Element.getStyle(this.update, 'display')!='none') this.options.onHide(this.element, this.update);
    if(this.iefix) Element.hide(this.iefix);
  },

  startIndicator: function() {
    if(this.options.indicator) Element.show(this.options.indicator);
  },

  stopIndicator: function() {
    if(this.options.indicator) Element.hide(this.options.indicator);
  },

  onKeyPress: function(event) {
    if(this.active)
      switch(event.keyCode) {
       case Event.KEY_TAB:
       case Event.KEY_RETURN:
         this.selectEntry();
         Event.stop(event);
       case Event.KEY_ESC:
         this.hide();
         this.active = false;
         Event.stop(event);
         return;
       case Event.KEY_LEFT:
       case Event.KEY_RIGHT:
         return;
       case Event.KEY_UP:
         this.markPrevious();
         this.render();
         if(Prototype.Browser.WebKit) Event.stop(event);
         return;
       case Event.KEY_DOWN:
         this.markNext();
         this.render();
         if(Prototype.Browser.WebKit) Event.stop(event);
         return;
      }
     else 
       if(event.keyCode==Event.KEY_TAB || event.keyCode==Event.KEY_RETURN || 
         (Prototype.Browser.WebKit > 0 && event.keyCode == 0)) return;

    this.changed = true;
    this.hasFocus = true;

    if(this.observer) clearTimeout(this.observer);
      this.observer = 
        setTimeout(this.onObserverEvent.bind(this), this.options.frequency*1000);
  },

  activate: function() {
    this.changed = false;
    this.hasFocus = true;
    this.getUpdatedChoices();
  },

  onHover: function(event) {
    var element = Event.findElement(event, 'LI');
    if(this.index != element.autocompleteIndex) 
    {
        this.index = element.autocompleteIndex;
        this.render();
    }
    Event.stop(event);
  },
  
  onClick: function(event) {
    var element = Event.findElement(event, 'LI');
    this.index = element.autocompleteIndex;
    this.selectEntry();
    this.hide();
  },
  
  onBlur: function(event) {
    // needed to make click events working
    setTimeout(this.hide.bind(this), 250);
    this.hasFocus = false;
    this.active = false;     
  }, 
  
  render: function() {
    if(this.entryCount > 0) {
      for (var i = 0; i < this.entryCount; i++)
        this.index==i ? 
          Element.addClassName(this.getEntry(i),"selected") : 
          Element.removeClassName(this.getEntry(i),"selected");
        
      if(this.hasFocus) { 
        this.show();
        this.active = true;
      }
    } else {
      this.active = false;
      this.hide();
    }
  },
  
  markPrevious: function() {
    if(this.index > 0) this.index--;
      else this.index = this.entryCount-1;
    this.getEntry(this.index).scrollIntoView(true);
  },
  
  markNext: function() {
    if(this.index < this.entryCount-1) this.index++;
      else this.index = 0;
    this.getEntry(this.index).scrollIntoView(false);
  },
  
  getEntry: function(index) {
    return this.update.firstChild.childNodes[index];
  },
  
  getCurrentEntry: function() {
    return this.getEntry(this.index);
  },
  
  selectEntry: function() {
    this.active = false;
    this.updateElement(this.getCurrentEntry());
  },

  updateElement: function(selectedElement) {
    if (this.options.updateElement) {
      this.options.updateElement(selectedElement);
      return;
    }
    var value = '';
    if (this.options.select) {
      var nodes = $$("#" + this.update.id + " .selected" + " ." + this.options.select);
      if(nodes.length > 0) value = Element.collectTextNodes(nodes[0], this.options.select);
    } else
      value = Element.collectTextNodesIgnoreClass(selectedElement, 'informal');
    
    var lastTokenPos = this.findLastToken();
    if (lastTokenPos != -1) {
      var newValue = this.element.value.substr(0, lastTokenPos + 1);
      var whitespace = this.element.value.substr(lastTokenPos + 1).match(/^\s+/);
      if (whitespace)
        newValue += whitespace[0];
      this.element.value = newValue + value;
    } else {
      this.element.value = value;
    }
    this.element.focus();
    
    if (this.options.afterUpdateElement)
      this.options.afterUpdateElement(this.element, selectedElement);
  },

  updateChoices: function(choices) {
    if(!this.changed && this.hasFocus) {
      this.update.innerHTML = choices;
      Element.cleanWhitespace(this.update);
      Element.cleanWhitespace(this.update.down());

      if(this.update.firstChild && this.update.down().childNodes) {
        this.entryCount = 
          this.update.down().childNodes.length;
        for (var i = 0; i < this.entryCount; i++) {
          var entry = this.getEntry(i);
          entry.autocompleteIndex = i;
          this.addObservers(entry);
        }
      } else { 
        this.entryCount = 0;
      }

      this.stopIndicator();
      this.index = 0;
      
      if(this.entryCount==1 && this.options.autoSelect) {
        this.selectEntry();
        this.hide();
      } else {
        this.render();
      }
    }
  },

  addObservers: function(element) {
    Event.observe(element, "mouseover", this.onHover.bindAsEventListener(this));
    Event.observe(element, "click", this.onClick.bindAsEventListener(this));
  },

  onObserverEvent: function() {
    this.changed = false;   
    if(this.getToken().length>=this.options.minChars) {
      this.startIndicator();
      this.getUpdatedChoices();
    } else {
      this.active = false;
      this.hide();
    }
  },

  getToken: function() {
    var tokenPos = this.findLastToken();
    if (tokenPos != -1)
      var ret = this.element.value.substr(tokenPos + 1).replace(/^\s+/,'').replace(/\s+$/,'');
    else
      var ret = this.element.value;

    return /\n/.test(ret) ? '' : ret;
  },

  findLastToken: function() {
    var lastTokenPos = -1;

    for (var i=0; i<this.options.tokens.length; i++) {
      var thisTokenPos = this.element.value.lastIndexOf(this.options.tokens[i]);
      if (thisTokenPos > lastTokenPos)
        lastTokenPos = thisTokenPos;
    }
    return lastTokenPos;
  }
};

Ajax.Autocompleter = Class.create();
Object.extend(Object.extend(Ajax.Autocompleter.prototype, Autocompleter.Base.prototype), {
  initialize: function(element, update, url, options) {
    this.baseInitialize(element, update, options);
    this.options.asynchronous  = true;
    this.options.onComplete    = this.onComplete.bind(this);
    this.options.defaultParams = this.options.parameters || null;
    this.url                   = url;
  },

  getUpdatedChoices: function() {
    entry = encodeURIComponent(this.options.paramName) + '=' + 
      encodeURIComponent(this.getToken());

    this.options.parameters = this.options.callback ?
      this.options.callback(this.element, entry) : entry;

    if(this.options.defaultParams) 
      this.options.parameters += '&' + this.options.defaultParams;

    new Ajax.Request(this.url, this.options);
  },

  onComplete: function(request) {
    this.updateChoices(request.responseText);
  }

});

// The local array autocompleter. Used when you'd prefer to
// inject an array of autocompletion options into the page, rather
// than sending out Ajax queries, which can be quite slow sometimes.
//
// The constructor takes four parameters. The first two are, as usual,
// the id of the monitored textbox, and id of the autocompletion menu.
// The third is the array you want to autocomplete from, and the fourth
// is the options block.
//
// Extra local autocompletion options:
// - choices - How many autocompletion choices to offer
//
// - partialSearch - If false, the autocompleter will match entered
//                    text only at the beginning of strings in the 
//                    autocomplete array. Defaults to true, which will
//                    match text at the beginning of any *word* in the
//                    strings in the autocomplete array. If you want to
//                    search anywhere in the string, additionally set
//                    the option fullSearch to true (default: off).
//
// - fullSsearch - Search anywhere in autocomplete array strings.
//
// - partialChars - How many characters to enter before triggering
//                   a partial match (unlike minChars, which defines
//                   how many characters are required to do any match
//                   at all). Defaults to 2.
//
// - ignoreCase - Whether to ignore case when autocompleting.
//                 Defaults to true.
//
// It's possible to pass in a custom function as the 'selector' 
// option, if you prefer to write your own autocompletion logic.
// In that case, the other options above will not apply unless
// you support them.

Autocompleter.Local = Class.create();
Autocompleter.Local.prototype = Object.extend(new Autocompleter.Base(), {
  initialize: function(element, update, array, options) {
    this.baseInitialize(element, update, options);
    this.options.array = array;
  },

  getUpdatedChoices: function() {
    this.updateChoices(this.options.selector(this));
  },

  setOptions: function(options) {
    this.options = Object.extend({
      choices: 10,
      partialSearch: true,
      partialChars: 2,
      ignoreCase: true,
      fullSearch: false,
      selector: function(instance) {
        var ret       = []; // Beginning matches
        var partial   = []; // Inside matches
        var entry     = instance.getToken();
        var count     = 0;

        for (var i = 0; i < instance.options.array.length &&  
          ret.length < instance.options.choices ; i++) { 

          var elem = instance.options.array[i];
          var foundPos = instance.options.ignoreCase ? 
            elem.toLowerCase().indexOf(entry.toLowerCase()) : 
            elem.indexOf(entry);

          while (foundPos != -1) {
            if (foundPos == 0 && elem.length != entry.length) { 
              ret.push("<li><strong>" + elem.substr(0, entry.length) + "</strong>" + 
                elem.substr(entry.length) + "</li>");
              break;
            } else if (entry.length >= instance.options.partialChars && 
              instance.options.partialSearch && foundPos != -1) {
              if (instance.options.fullSearch || /\s/.test(elem.substr(foundPos-1,1))) {
                partial.push("<li>" + elem.substr(0, foundPos) + "<strong>" +
                  elem.substr(foundPos, entry.length) + "</strong>" + elem.substr(
                  foundPos + entry.length) + "</li>");
                break;
              }
            }

            foundPos = instance.options.ignoreCase ? 
              elem.toLowerCase().indexOf(entry.toLowerCase(), foundPos + 1) : 
              elem.indexOf(entry, foundPos + 1);

          }
        }
        if (partial.length)
          ret = ret.concat(partial.slice(0, instance.options.choices - ret.length));
        return "<ul>" + ret.join('') + "</ul>";
      }
    }, options || {});
  }
});

// AJAX in-place editor
//
// see documentation on http://wiki.script.aculo.us/scriptaculous/show/Ajax.InPlaceEditor

// Use this if you notice weird scrolling problems on some browsers,
// the DOM might be a bit confused when this gets called so do this
// waits 1 ms (with setTimeout) until it does the activation
Field.scrollFreeActivate = function(field) {
  setTimeout(function() {
    Field.activate(field);
  }, 1);
};

Ajax.InPlaceEditor = Class.create();
Ajax.InPlaceEditor.defaultHighlightColor = "#FFFF99";
Ajax.InPlaceEditor.prototype = {
  initialize: function(element, url, options) {
    this.url = url;
    this.element = $(element);

    this.options = Object.extend({
      paramName: "value",
      okButton: true,
      okLink: false,
      okText: "ok",
      cancelButton: false,
      cancelLink: true,
      cancelText: "cancel",
      textBeforeControls: '',
      textBetweenControls: '',
      textAfterControls: '',
      savingText: 'js_saving'.tr(),
      clickToEditText: 'js_click_to_edit'.tr(),
      okText: "ok",
      rows: 1,
      onComplete: function(transport, element) {
        new Effect.Highlight(element, {startcolor: this.options.highlightcolor});
      },
      onFailure: function(transport) {
        alert('js_error_communicating_with_server'.tr() + transport.responseText.stripTags());
      },
      callback: function(form) {
        return Form.serialize(form);
      },
      handleLineBreaks: true,
      loadingText: 'Loading...',
      savingClassName: 'inplaceeditor-saving',
      loadingClassName: 'inplaceeditor-loading',
      formClassName: 'inplaceeditor-form',
      highlightcolor: Ajax.InPlaceEditor.defaultHighlightColor,
      highlightendcolor: "#FFFFFF",
      externalControl: null,
      submitOnBlur: false,
      ajaxOptions: {},
      evalScripts: false
    }, options || {});

    if(!this.options.formId && this.element.id) {
      this.options.formId = this.element.id + "-inplaceeditor";
      if ($(this.options.formId)) {
        // there's already a form with that name, don't specify an id
        this.options.formId = null;
      }
    }
    
    if (this.options.externalControl) {
      this.options.externalControl = $(this.options.externalControl);
    }
    
    this.originalBackground = Element.getStyle(this.element, 'background-color');
    if (!this.originalBackground) {
      this.originalBackground = "transparent";
    }
    
    this.element.title = this.options.clickToEditText;
    
    this.onclickListener = this.enterEditMode.bindAsEventListener(this);
    this.mouseoverListener = this.enterHover.bindAsEventListener(this);
    this.mouseoutListener = this.leaveHover.bindAsEventListener(this);
    Event.observe(this.element, 'click', this.onclickListener);
    Event.observe(this.element, 'mouseover', this.mouseoverListener);
    Event.observe(this.element, 'mouseout', this.mouseoutListener);
    if (this.options.externalControl) {
      Event.observe(this.options.externalControl, 'click', this.onclickListener);
      Event.observe(this.options.externalControl, 'mouseover', this.mouseoverListener);
      Event.observe(this.options.externalControl, 'mouseout', this.mouseoutListener);
    }
  },
  enterEditMode: function(evt) {
    if (this.saving) return;
    if (this.editing) return;
    this.editing = true;
    this.onEnterEditMode();
    if (this.options.externalControl) {
      Element.hide(this.options.externalControl);
    }
    Element.hide(this.element);
    this.createForm();
    this.element.parentNode.insertBefore(this.form, this.element);
    if (!this.options.loadTextURL) Field.scrollFreeActivate(this.editField);
    // stop the event to avoid a page refresh in Safari
    if (evt) {
      Event.stop(evt);
    }
    return false;
  },
  createForm: function() {
    this.form = document.createElement("form");
    this.form.id = this.options.formId;
    Element.addClassName(this.form, this.options.formClassName);
    this.form.onsubmit = this.onSubmit.bind(this);

    this.createEditField();

    if (this.options.textarea) {
      var br = document.createElement("br");
      this.form.appendChild(br);
    }
    
    if (this.options.textBeforeControls)
      this.form.appendChild(document.createTextNode(this.options.textBeforeControls));

    if (this.options.okButton) {
      var okButton = document.createElement("input");
      okButton.type = "submit";
      okButton.value = this.options.okText;
      okButton.className = 'editor_ok_button';
      this.form.appendChild(okButton);
    }
    
    if (this.options.okLink) {
      var okLink = document.createElement("a");
      okLink.href = "#";
      okLink.appendChild(document.createTextNode(this.options.okText));
      okLink.onclick = this.onSubmit.bind(this);
      okLink.className = 'editor_ok_link';
      this.form.appendChild(okLink);
    }
    
    if (this.options.textBetweenControls && 
      (this.options.okLink || this.options.okButton) && 
      (this.options.cancelLink || this.options.cancelButton))
      this.form.appendChild(document.createTextNode(this.options.textBetweenControls));
      
    if (this.options.cancelButton) {
      var cancelButton = document.createElement("input");
      cancelButton.type = "submit";
      cancelButton.value = this.options.cancelText;
      cancelButton.onclick = this.onclickCancel.bind(this);
      cancelButton.className = 'editor_cancel_button';
      this.form.appendChild(cancelButton);
    }

    if (this.options.cancelLink) {
      var cancelLink = document.createElement("a");
      cancelLink.href = "#";
      cancelLink.appendChild(document.createTextNode(this.options.cancelText));
      cancelLink.onclick = this.onclickCancel.bind(this);
      cancelLink.className = 'editor_cancel editor_cancel_link';      
      this.form.appendChild(cancelLink);
    }
    
    if (this.options.textAfterControls)
      this.form.appendChild(document.createTextNode(this.options.textAfterControls));
  },
  hasHTMLLineBreaks: function(string) {
    if (!this.options.handleLineBreaks) return false;
    return string.match(/<br/i) || string.match(/<p>/i);
  },
  convertHTMLLineBreaks: function(string) {
    return string.replace(/<br>/gi, "\n").replace(/<br\/>/gi, "\n").replace(/<\/p>/gi, "\n").replace(/<p>/gi, "");
  },
  createEditField: function() {
    var text;
    if(this.options.loadTextURL) {
      text = this.options.loadingText;
    } else {
      text = this.getText();
    }

    var obj = this;
    
    if (this.options.rows == 1 && !this.hasHTMLLineBreaks(text)) {
      this.options.textarea = false;
      var textField = document.createElement("input");
      textField.obj = this;
      textField.type = "text";
      textField.name = this.options.paramName;
      textField.value = text;
      textField.style.backgroundColor = this.options.highlightcolor;
      textField.className = 'editor_field';
      var size = this.options.size || this.options.cols || 0;
      if (size != 0) textField.size = size;
      if (this.options.submitOnBlur)
        textField.onblur = this.onSubmit.bind(this);
      this.editField = textField;
    } else {
      this.options.textarea = true;
      var textArea = document.createElement("textarea");
      textArea.obj = this;
      textArea.name = this.options.paramName;
      textArea.value = this.convertHTMLLineBreaks(text);
      textArea.rows = this.options.rows;
      textArea.cols = this.options.cols || 40;
      textArea.className = 'editor_field';      
      if (this.options.submitOnBlur)
        textArea.onblur = this.onSubmit.bind(this);
      this.editField = textArea;
    }
    
    if(this.options.loadTextURL) {
      this.loadExternalText();
    }
    this.form.appendChild(this.editField);
  },
  getText: function() {
    return this.element.innerHTML;
  },
  loadExternalText: function() {
    Element.addClassName(this.form, this.options.loadingClassName);
    this.editField.disabled = true;
    new Ajax.Request(
      this.options.loadTextURL,
      Object.extend({
        asynchronous: true,
        onComplete: this.onLoadedExternalText.bind(this)
      }, this.options.ajaxOptions)
    );
  },
  onLoadedExternalText: function(transport) {
    Element.removeClassName(this.form, this.options.loadingClassName);
    this.editField.disabled = false;
    this.editField.value = transport.responseText.stripTags();
    Field.scrollFreeActivate(this.editField);
  },
  onclickCancel: function() {
    this.onComplete();
    this.leaveEditMode();
    return false;
  },
  onFailure: function(transport) {
    this.options.onFailure(transport);
    if (this.oldInnerHTML) {
      this.element.innerHTML = this.oldInnerHTML;
      this.oldInnerHTML = null;
    }
    return false;
  },
  onSubmit: function() {
    // onLoading resets these so we need to save them away for the Ajax call
    var form = this.form;
    var value = this.editField.value;
    
    // do this first, sometimes the ajax call returns before we get a chance to switch on Saving...
    // which means this will actually switch on Saving... *after* we've left edit mode causing Saving...
    // to be displayed indefinitely
    this.onLoading();
    
    if (this.options.evalScripts) {
      new Ajax.Request(
        this.url, Object.extend({
          parameters: this.options.callback(form, value),
          onComplete: this.onComplete.bind(this),
          onFailure: this.onFailure.bind(this),
          asynchronous:true, 
          evalScripts:true
        }, this.options.ajaxOptions));
    } else  {
      new Ajax.Updater(
        { success: this.element,
          // don't update on failure (this could be an option)
          failure: null }, 
        this.url, Object.extend({
          parameters: this.options.callback(form, value),
          onComplete: this.onComplete.bind(this),
          onFailure: this.onFailure.bind(this)
        }, this.options.ajaxOptions));
    }
    // stop the event to avoid a page refresh in Safari
    if (arguments.length > 1) {
      Event.stop(arguments[0]);
    }
    return false;
  },
  onLoading: function() {
    this.saving = true;
    this.removeForm();
    this.leaveHover();
    this.showSaving();
  },
  showSaving: function() {
    this.oldInnerHTML = this.element.innerHTML;
    this.element.innerHTML = this.options.savingText;
    Element.addClassName(this.element, this.options.savingClassName);
    this.element.style.backgroundColor = this.originalBackground;
    Element.show(this.element);
  },
  removeForm: function() {
    if(this.form) {
      if (this.form.parentNode) Element.remove(this.form);
      this.form = null;
    }
  },
  enterHover: function() {
    if (this.saving) return;
    this.element.style.backgroundColor = this.options.highlightcolor;
    if (this.effect) {
      this.effect.cancel();
    }
    Element.addClassName(this.element, this.options.hoverClassName);
  },
  leaveHover: function() {
    if (this.options.backgroundColor) {
      this.element.style.backgroundColor = this.oldBackground;
    }
    Element.removeClassName(this.element, this.options.hoverClassName);
    if (this.saving) return;
    this.effect = new Effect.Highlight(this.element, {
      startcolor: this.options.highlightcolor,
      endcolor: this.options.highlightendcolor,
      restorecolor: this.originalBackground
    });
  },
  leaveEditMode: function() {
    Element.removeClassName(this.element, this.options.savingClassName);
    this.removeForm();
    this.leaveHover();
    this.element.style.backgroundColor = this.originalBackground;
    Element.show(this.element);
    if (this.options.externalControl) {
      Element.show(this.options.externalControl);
    }
    this.editing = false;
    this.saving = false;
    this.oldInnerHTML = null;
    this.onLeaveEditMode();
  },
  onComplete: function(transport) {
    this.leaveEditMode();
    this.options.onComplete.bind(this)(transport, this.element);
  },
  onEnterEditMode: function() {},
  onLeaveEditMode: function() {},
  dispose: function() {
    if (this.oldInnerHTML) {
      this.element.innerHTML = this.oldInnerHTML;
    }
    this.leaveEditMode();
    Event.stopObserving(this.element, 'click', this.onclickListener);
    Event.stopObserving(this.element, 'mouseover', this.mouseoverListener);
    Event.stopObserving(this.element, 'mouseout', this.mouseoutListener);
    if (this.options.externalControl) {
      Event.stopObserving(this.options.externalControl, 'click', this.onclickListener);
      Event.stopObserving(this.options.externalControl, 'mouseover', this.mouseoverListener);
      Event.stopObserving(this.options.externalControl, 'mouseout', this.mouseoutListener);
    }
  }
};

Ajax.InPlaceCollectionEditor = Class.create();
Object.extend(Ajax.InPlaceCollectionEditor.prototype, Ajax.InPlaceEditor.prototype);
Object.extend(Ajax.InPlaceCollectionEditor.prototype, {
  createEditField: function() {
    if (!this.cached_selectTag) {
      var selectTag = document.createElement("select");
      var collection = this.options.collection || [];
      var optionTag;
      collection.each(function(e,i) {
        optionTag = document.createElement("option");
        optionTag.value = (e instanceof Array) ? e[0] : e;
        if((typeof this.options.value == 'undefined') && 
          ((e instanceof Array) ? this.element.innerHTML == e[1] : e == optionTag.value)) optionTag.selected = true;
        if(this.options.value==optionTag.value) optionTag.selected = true;
        optionTag.appendChild(document.createTextNode((e instanceof Array) ? e[1] : e));
        selectTag.appendChild(optionTag);
      }.bind(this));
      this.cached_selectTag = selectTag;
    }

    this.editField = this.cached_selectTag;
    if(this.options.loadTextURL) this.loadExternalText();
    this.form.appendChild(this.editField);
    this.options.callback = function(form, value) {
      return "value=" + encodeURIComponent(value);
    };
  }
});

// Delayed observer, like Form.Element.Observer, 
// but waits for delay after last key input
// Ideal for live-search fields

Form.Element.DelayedObserver = Class.create();
Form.Element.DelayedObserver.prototype = {
  initialize: function(element, delay, callback) {
    this.delay     = delay || 0.5;
    this.element   = $(element);
    this.callback  = callback;
    this.timer     = null;
    this.lastValue = $F(this.element); 
    Event.observe(this.element,'keyup',this.delayedListener.bindAsEventListener(this));
  },
  delayedListener: function(event) {
    if(this.lastValue == $F(this.element)) return;
    if(this.timer) clearTimeout(this.timer);
    this.timer = setTimeout(this.onTimerEvent.bind(this), this.delay * 1000);
    this.lastValue = $F(this.element);
  },
  onTimerEvent: function() {
    this.timer = null;
    this.callback(this.element, $F(this.element));
  }
};
// Copyright (c) 2006 Sébastien Gruhier (http://xilinus.com, http://itseb.com)
//
// Permission is hereby granted, free of charge, to any person obtaining
// a copy of this software and associated documentation files (the
// "Software"), to deal in the Software without restriction, including
// without limitation the rights to use, copy, modify, merge, publish,
// distribute, sublicense, and/or sell copies of the Software, and to
// permit persons to whom the Software is furnished to do so, subject to
// the following conditions:
//
// The above copyright notice and this permission notice shall be
// included in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
// EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
// NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
// LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
// OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
// WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
//
// VERSION 1.3

var Window = Class.create();

Window.keepMultiModalWindow = false;
Window.hasEffectLib = (typeof Effect != 'undefined');
Window.resizeEffectDuration = 0.4;

Window.prototype = {
    // Constructor
    // Available parameters : className, blurClassName, title, minWidth, minHeight, maxWidth, maxHeight, width, height, top, left, bottom, right, resizable, zIndex, opacity, recenterAuto, wiredDrag
    //                        hideEffect, showEffect, showEffectOptions, hideEffectOptions, effectOptions, url, draggable, closable, minimizable, maximizable, parent, onload
    //                        add all callbacks (if you do not use an observer)
    //                        onDestroy onStartResize onStartMove onResize onMove onEndResize onEndMove onFocus onBlur onBeforeShow onShow onHide onMinimize onMaximize onClose

    initialize: function() {
        var id;
        var optionIndex = 0;
    // For backward compatibility like win= new Window("id", {...}) instead of win = new Window({id: "id", ...})
        if (arguments.length > 0) {
            if (typeof arguments[0] == "string" ) {
                id = arguments[0];
                optionIndex = 1;
            }
            else
                id = arguments[0] ? arguments[0].id : null;
        }

    // Generate unique ID if not specified
        if (!id)
            id = "window_" + new Date().getTime();

        if ($(id))
            return;
            //alert("Window " + id + " is already registered in the DOM! Make sure you use setDestroyOnClose() or destroyOnClose: true in the constructor");

        this.options = Object.extend({
            className:         "mmdialog",
            blurClassName:     null,
            minWidth:          100,
            minHeight:         20,
            resizable:         true,
            closable:          true,
            minimizable:       true,
            maximizable:       true,
            draggable:         true,
            userData:          null,
            showEffect:        (Window.hasEffectLib ? Effect.Appear : Element.show),
            hideEffect:        (Window.hasEffectLib ? Effect.Fade : Element.hide),
            showEffectOptions: {},
            hideEffectOptions: {},
            useEffects:        true,
            effectOptions:     null,
            useCSS3:           false, // mhollauf mindmeister
            parent:            document.body,
            title:             "&nbsp;",
            url:               null,
            onload:            Prototype.emptyFunction,
            width:             200,
            height:            300,
            opacity:           1,
            recenterAuto:      true,
            wiredDrag:         false,
            closeCallback:     null,
            destroyOnClose:    false,
            gridX:             1,
            gridY:             1
        }, arguments[optionIndex] || {});
        if (this.options.blurClassName)
            this.options.focusClassName = this.options.className;

        if (typeof this.options.top == "undefined" &&  typeof this.options.bottom ==  "undefined")
            this.options.top = this._round(Math.random()*500, this.options.gridY);
        if (typeof this.options.left == "undefined" &&  typeof this.options.right ==  "undefined")
            this.options.left = this._round(Math.random()*500, this.options.gridX);

        if (this.options.effectOptions) {
            Object.extend(this.options.hideEffectOptions, this.options.effectOptions);
            Object.extend(this.options.showEffectOptions, this.options.effectOptions);
            if (this.options.showEffect == Element.Appear)
                this.options.showEffectOptions.to = this.options.opacity;
        }
        if (Window.hasEffectLib) {
            if (this.options.showEffect == Effect.Appear)
                this.options.showEffectOptions.to = this.options.opacity;

            if (this.options.hideEffect == Effect.Fade)
                this.options.hideEffectOptions.from = this.options.opacity;
        }
        if (this.options.hideEffect == Element.hide)
            this.options.hideEffect = function(){ Element.hide(this.element); if (this.options.destroyOnClose) this.destroy(); }.bind(this);

        if (this.options.parent != document.body)
            this.options.parent = $(this.options.parent);

        this.element = this._createWindow(id);
        this.element.win = this;

    // Bind event listener
        this.eventMouseDown = this._initDrag.bindAsEventListener(this);
        this.eventMouseUp   = this._endDrag.bindAsEventListener(this);
        this.eventMouseMove = this._updateDrag.bindAsEventListener(this);
        this.eventOnLoad    = this._getWindowBorderSize.bindAsEventListener(this);
        this.eventMouseDownContent = this.toFront.bindAsEventListener(this);
        this.eventResize = this._recenter.bindAsEventListener(this);

        this.content = $(this.element.id + "_content");
        
        if (!this.options.useCSS3) {
          this.topbar = $(this.element.id + "_top");
          this.bottombar = $(this.element.id + "_bottom");

          Event.observe(this.topbar, "mousedown", this.eventMouseDown);
          Event.observe(this.bottombar, "mousedown", this.eventMouseDown);
          Event.observe(this.content, "mousedown", this.eventMouseDownContent);

          if (this.options.draggable)  {
              var that = this;
              [this.topbar, this.topbar.up().previous(), this.topbar.up().next()].each(function(element) {
                  element.observe("mousedown", that.eventMouseDown);
                  element.addClassName("top_draggable");
              });
              [this.bottombar.up(), this.bottombar.up().previous(), this.bottombar.up().next()].each(function(element) {
                  element.observe("mousedown", that.eventMouseDown);
                  element.addClassName("bottom_draggable");
              });

          }
        }
        Event.observe(window, "load", this.eventOnLoad);
        Event.observe(window, "resize", this.eventResize);
        Event.observe(window, "scroll", this.eventResize);
        Event.observe(this.options.parent, "scroll", this.eventResize);

        if (this.options.resizable) {
            this.sizer = $(this.element.id + "_sizer");
            Event.observe(this.sizer, "mousedown", this.eventMouseDown);
        }

        this.useLeft = null;
        this.useTop = null;
        if (typeof this.options.left != "undefined") {
            this.element.setStyle({left: parseFloat(this.options.left) + 'px'});
            this.useLeft = true;
        }
        else {
            this.element.setStyle({right: parseFloat(this.options.right) + 'px'});
            this.useLeft = false;
        }

        if (typeof this.options.top != "undefined") {
            this.element.setStyle({top: parseFloat(this.options.top) + 'px'});
            this.useTop = true;
        }
        else {
            this.element.setStyle({bottom: parseFloat(this.options.bottom) + 'px'});
            this.useTop = false;
        }

        this.storedLocation = null;

        this.setOpacity(this.options.opacity);
        if (this.options.zIndex)
            this.setZIndex(this.options.zIndex);

        if (this.options.destroyOnClose)
            this.setDestroyOnClose(true);

        this._getWindowBorderSize();
        this.width = this.options.width;
        this.height = this.options.height;
        this.visible = false;

        this.constraint = false;
        this.constraintPad = {top: 0, left:0, bottom:0, right:0};

        if (this.width && this.height)
            this.setSize(this.options.width, this.options.height);
        this.setTitle(this.options.title);
        Windows.register(this);
    },

    // Destructor
    destroy: function() {
        this._notify("onDestroy");
        Event.stopObserving(this.topbar, "mousedown", this.eventMouseDown);
        Event.stopObserving(this.bottombar, "mousedown", this.eventMouseDown);
        Event.stopObserving(this.content, "mousedown", this.eventMouseDownContent);

        Event.stopObserving(window, "load", this.eventOnLoad);
        Event.stopObserving(window, "resize", this.eventResize);
        Event.stopObserving(window, "scroll", this.eventResize);

        Event.stopObserving(this.content, "load", this.options.onload);

        if (this._oldParent) {
            var content = this.getContent();
            var originalContent = null;
            for(var i = 0; i < content.childNodes.length; i++) {
                originalContent = content.childNodes[i];
                if (originalContent.nodeType == 1)
                    break;
                originalContent = null;
            }
            if (originalContent)
                this._oldParent.appendChild(originalContent);
            this._oldParent = null;
        }

        if (this.sizer)
            Event.stopObserving(this.sizer, "mousedown", this.eventMouseDown);

        if (this.options.url)
            this.content.src = null;

        if(this.iefix)
            Element.remove(this.iefix);

        Element.remove(this.element);
        Windows.unregister(this);
    },

    // Sets close callback, if it sets, it should return true to be able to close the window.
    setCloseCallback: function(callback) {
        this.options.closeCallback = callback;
    },

    // Gets window content
    getContent: function () {
        return this.content;
    },

    // Sets the content with an element id
    setContent: function(id, autoresize, autoposition) {
        var element = $(id);
        if (null == element) throw "Unable to find element '" + id + "' in DOM";
        this._oldParent = element.parentNode;

        var d = null;
        var p = null;

        if (autoresize)
            d = Element.getDimensions(element);
        if (autoposition)
            p = Position.cumulativeOffset(element);

        var content = this.getContent();
    // Clear HTML (and even iframe)
        this.setHTMLContent("");
        content = this.getContent();

        content.appendChild(element);
        element.show();
        if (autoresize)
            this.setSize(d.width, d.height);
        if (autoposition)
            this.setLocation(p[1] - this.heightN, p[0] - this.widthW);
    },

    setHTMLContent: function(html) {
        // It was an url (iframe), recreate a div content instead of iframe content
        if (this.options.url) {
            this.content.src = null;
            this.options.url = null;

            var content ="<div id=\"" + this.getId() + "_content\" class=\"" + this.options.className + "_content\"> </div>";
            $(this.getId() +"_table_content").innerHTML = content;

            this.content = $(this.element.id + "_content");
        }

        this.getContent().innerHTML = html;
    },

    setAjaxContent: function(url, options, showCentered, showModal) {
        this.showFunction = showCentered ? "showCenter" : "show";
        this.showModal = showModal || false;

        options = options || {};

    // Clear HTML (and even iframe)
        this.setHTMLContent("");

        this.onComplete = options.onComplete;
        if (! this._onCompleteHandler)
            this._onCompleteHandler = this._setAjaxContent.bind(this);
        options.onComplete = this._onCompleteHandler;

        new Ajax.Request(url, options);
        options.onComplete = this.onComplete;
    },

    _setAjaxContent: function(originalRequest) {
        Element.update(this.getContent(), originalRequest.responseText);
        if (this.onComplete)
            this.onComplete(originalRequest);
        this.onComplete = null;
        this[this.showFunction](this.showModal);
    },

    setURL: function(url) {
        // Not an url content, change div to iframe
        if (this.options.url)
            this.content.src = null;
        this.options.url = url;
        var content= "<iframe frameborder='0' name='" + this.getId() + "_content'  id='" + this.getId() + "_content' src='" + url + "' width='" + this.width + "' height='" + this.height + "'> </iframe>";
        $(this.getId() +"_table_content").innerHTML = content;

        this.content = $(this.element.id + "_content");
    },

    getURL: function() {
        return this.options.url ? this.options.url : null;
    },

    refresh: function() {
        if (this.options.url)
            $(this.element.getAttribute('id') + '_content').src = this.options.url;
    },

    // Stores position/size in a cookie, by default named with window id
    setCookie: function(name, expires, path, domain, secure) {
        name = name || this.element.id;
        this.cookie = [name, expires, path, domain, secure];

    // Get cookie
        var value = WindowUtilities.getCookie(name);
    // If exists
        if (value) {
            var values = value.split(',');
            var x = values[0].split(':');
            var y = values[1].split(':');

            var w = parseFloat(values[2]), h = parseFloat(values[3]);
            var mini = values[4];
            var maxi = values[5];

            this.setSize(w, h);
            if (mini == "true")
                this.doMinimize = true; // Minimize will be done at onload window event
            else if (maxi == "true")
                this.doMaximize = true; // Maximize will be done at onload window event

            this.useLeft = x[0] == "l";
            this.useTop = y[0] == "t";

            this.element.setStyle(this.useLeft ? {left: x[1]} : {right: x[1]});
            this.element.setStyle(this.useTop ? {top: y[1]} : {bottom: y[1]});
        }
    },

    // Gets window ID
    getId: function() {
        return this.element.id;
    },

    // Detroys itself when closing
    setDestroyOnClose: function() {
        this.options.destroyOnClose = true;
    },

    setConstraint: function(bool, padding) {
        this.constraint = bool;
        this.constraintPad = Object.extend(this.constraintPad, padding || {});
    // Reset location to apply constraint
        if (this.useTop && this.useLeft)
            this.setLocation(parseFloat(this.element.style.top), parseFloat(this.element.style.left));
    },

    // initDrag event

    _initDrag: function(event) {
        // No resize on minimized window
        if (Event.element(event) == this.sizer && this.isMinimized())
            return;

    // No move on maximzed window
        if (Event.element(event) != this.sizer && this.isMaximized())
            return;

        if (Prototype.Browser.IE && this.heightN == 0)
            this._getWindowBorderSize();

    // Get pointer X,Y
        this.pointer = [this._round(Event.pointerX(event), this.options.gridX), this._round(Event.pointerY(event), this.options.gridY)];
        if (this.options.wiredDrag)
            this.currentDrag = this._createWiredElement();
        else
            this.currentDrag = this.element;

    // Resize
        if (Event.element(event) == this.sizer) {
            this.doResize = true;
            this.widthOrg = this.width;
            this.heightOrg = this.height;
            this.bottomOrg = parseFloat(this.element.getStyle('bottom'));
            this.rightOrg = parseFloat(this.element.getStyle('right'));
            this._notify("onStartResize");
        }
        else {
            this.doResize = false;

      // Check if click on close button,
            var closeButton = $(this.getId() + '_close');
            if (closeButton && Position.within(closeButton, this.pointer[0], this.pointer[1])) {
                this.currentDrag = null;
                return;
            }

            this.toFront();

            if (! this.options.draggable)
                return;
            this._notify("onStartMove");
        }
    // Register global event to capture mouseUp and mouseMove
        Event.observe(document, "mouseup", this.eventMouseUp, false);
        Event.observe(document, "mousemove", this.eventMouseMove, false);

    // Add an invisible div to keep catching mouse event over iframes
        WindowUtilities.disableScreen('__invisible__', '__invisible__', this.overlayOpacity);

    // Stop selection while dragging
        document.body.ondrag = function () { return false; };
        document.body.onselectstart = function () { return false; };

        this.currentDrag.show();
        Event.stop(event);
    },

    _round: function(val, round) {
        return round == 1 ? val  : val = Math.floor(val / round) * round;
    },

    // updateDrag event
    _updateDrag: function(event) {
        var pointer =  [this._round(Event.pointerX(event), this.options.gridX), this._round(Event.pointerY(event), this.options.gridY)];
        var dx = pointer[0] - this.pointer[0];
        var dy = pointer[1] - this.pointer[1];

    // Resize case, update width/height
        if (this.doResize) {
            var w = this.widthOrg + dx;
            var h = this.heightOrg + dy;

            dx = this.width - this.widthOrg;
            dy = this.height - this.heightOrg;

      // Check if it's a right position, update it to keep upper-left corner at the same position
            if (this.useLeft)
                w = this._updateWidthConstraint(w);
            else
                this.currentDrag.setStyle({right: (this.rightOrg -dx) + 'px'});
      // Check if it's a bottom position, update it to keep upper-left corner at the same position
            if (this.useTop)
                h = this._updateHeightConstraint(h);
            else
                this.currentDrag.setStyle({bottom: (this.bottomOrg -dy) + 'px'});

            this.setSize(w , h);
            this._notify("onResize");
        }
            // Move case, update top/left
        else {
            this.pointer = pointer;

            if (this.useLeft) {
                var left =  parseFloat(this.currentDrag.getStyle('left')) + dx;
                var newLeft = this._updateLeftConstraint(left);
        // Keep mouse pointer correct
                this.pointer[0] += newLeft-left;
                this.currentDrag.setStyle({left: newLeft + 'px'});
            }
            else
                this.currentDrag.setStyle({right: parseFloat(this.currentDrag.getStyle('right')) - dx + 'px'});

            if (this.useTop) {
                var top =  parseFloat(this.currentDrag.getStyle('top')) + dy;
                var newTop = this._updateTopConstraint(top);
        // Keep mouse pointer correct
                this.pointer[1] += newTop - top;
                this.currentDrag.setStyle({top: newTop + 'px'});
            }
            else
                this.currentDrag.setStyle({bottom: parseFloat(this.currentDrag.getStyle('bottom')) - dy + 'px'});

            this._notify("onMove");
        }
        if (this.iefix)
            this._fixIEOverlapping();

        this._removeStoreLocation();
        Event.stop(event);
    },

    // endDrag callback
    _endDrag: function(event) {
        // Remove temporary div over iframes
        WindowUtilities.enableScreen('__invisible__');

        if (this.doResize)
            this._notify("onEndResize");
        else
            this._notify("onEndMove");

    // Release event observing
        Event.stopObserving(document, "mouseup", this.eventMouseUp,false);
        Event.stopObserving(document, "mousemove", this.eventMouseMove, false);

        Event.stop(event);

        this._hideWiredElement();

    // Store new location/size if need be
        this._saveCookie();

    // Restore selection
        document.body.ondrag = null;
        document.body.onselectstart = null;
    },

    _updateLeftConstraint: function(left) {
        if (this.constraint && this.useLeft && this.useTop) {
            var width = this.options.parent == document.body ? WindowUtilities.getPageSize().windowWidth : this.options.parent.getDimensions().width;

            if (left < this.constraintPad.left)
                left = this.constraintPad.left;
            if (left + this.width + this.widthE + this.widthW > width - this.constraintPad.right)
                left = width - this.constraintPad.right - this.width - this.widthE - this.widthW;
        }
        return left;
    },

    _updateTopConstraint: function(top) {
        if (this.constraint && this.useLeft && this.useTop) {
            var height = this.options.parent == document.body ? WindowUtilities.getPageSize().windowHeight : this.options.parent.getDimensions().height;

            var h = this.height + this.heightN + this.heightS;

            if (top < this.constraintPad.top)
                top = this.constraintPad.top;
            if (top + h > height - this.constraintPad.bottom)
                top = height - this.constraintPad.bottom - h;
        }
        return top;
    },

    _updateWidthConstraint: function(w) {
        if (this.constraint && this.useLeft && this.useTop) {
            var width = this.options.parent == document.body ? WindowUtilities.getPageSize().windowWidth : this.options.parent.getDimensions().width;
            var left =  parseFloat(this.element.getStyle("left"));

            if (left + w + this.widthE + this.widthW > width - this.constraintPad.right)
                w = width - this.constraintPad.right - left - this.widthE - this.widthW;
        }
        return w;
    },

    _updateHeightConstraint: function(h) {
        if (this.constraint && this.useLeft && this.useTop) {
            var height = this.options.parent == document.body ? WindowUtilities.getPageSize().windowHeight : this.options.parent.getDimensions().height;
            var top =  parseFloat(this.element.getStyle("top"));

            if (top + h + this.heightN + this.heightS > height - this.constraintPad.bottom)
                h = height - this.constraintPad.bottom - top - this.heightN - this.heightS;
        }
        return h;
    },


    // Creates HTML window code
    _createWindow: function(id) {
        var className = this.options.className;
        var win = document.createElement("div");
        win.setAttribute('id', id);
        win.className = "mmdialog";

        var content;
        if (this.options.url)
            content= "<iframe frameborder=\"0\" name=\"" + id + "_content\"  id=\"" + id + "_content\" src=\"" + this.options.url + "\"> </iframe>";
        else
            content ="<div id=\"" + id + "_content\" class=\"" +className + "_content\"> </div>";

        var closeDiv = this.options.closable ? "<div class='"+ className +"_close' id='"+ id +"_close' onclick='Windows.close(\""+ id +"\", event)'> </div>" : "";
        var minDiv = this.options.minimizable ? "<div class='"+ className + "_minimize' id='"+ id +"_minimize' onclick='Windows.minimize(\""+ id +"\", event)'> </div>" : "";
        var maxDiv = this.options.maximizable ? "<div class='"+ className + "_maximize' id='"+ id +"_maximize' onclick='Windows.maximize(\""+ id +"\", event)'> </div>" : "";
        var seAttributes = this.options.resizable ? "class='" + className + "_sizer' id='" + id + "_sizer'" : "class='"  + className + "_se'";
        var blank = "../themes/default/blank.gif";

        if (this.options.useCSS3) {
          win.className = this.options.className + (this.options.className=="popup" ? "_css" : "") + (this.options.height < 300 ? " small" : "");
          win.innerHTML = closeDiv + minDiv + maxDiv + "<h1 id='"+ id +"_top'>"+ this.options.title +"</h1>" + content;
        } else {

        win.innerHTML = closeDiv + minDiv + maxDiv + "\
      <table id='"+ id +"_row1' class=\"top table_window" + (this.options.tableClass ? " "+this.options.tableClass : "") + "\">\
        <tr>\
          <td class='"+ className +"_nw'></td>\
          <td class='"+ className +"_n'><div id='"+ id +"_top' class='"+ className +"_title title_window'>"+ this.options.title +"</div></td>\
          <td class='"+ className +"_ne'></td>\
        </tr>\
      </table>\
      <table id='"+ id +"_row2' class=\"mid table_window\">\
        <tr>\
          <td class='"+ className +"_w'></td>\
            <td id='"+ id +"_table_content' class='"+ className +"_content' valign='top'>" + content + "</td>\
          <td class='"+ className +"_e'></td>\
        </tr>\
      </table>\
        <table id='"+ id +"_row3' class=\"bot table_window\">\
        <tr>\
          <td class='"+ className +"_sw'></td>\
            <td class='"+ className +"_s'><div id='"+ id +"_bottom' class='status_bar'><span style='float:left; width:1px; height:1px'></span></div></td>\
            <td " + seAttributes + "></td>\
        </tr>\
      </table>\
    ";
        }
        Element.hide(win);
        this.options.parent.insertBefore(win, this.options.parent.firstChild);
        Event.observe($(id + "_content"), "load", this.options.onload);
        return win;
    },


    changeClassName: function(newClassName) {
        var className = this.options.className;
        var id = this.getId();
        $A(["_close", "_minimize", "_maximize", "_sizer", "_content"]).each(function(value) { this._toggleClassName($(id + value), className + value, newClassName + value); }.bind(this));
        this._toggleClassName($(id + "_top"), className + "_title", newClassName + "_title");
        $$("#" + id + " td").each(function(td) {td.className = td.className.sub(className,newClassName); });
        this.options.className = newClassName;
    },

    _toggleClassName: function(element, oldClassName, newClassName) {
        if (element) {
            element.removeClassName(oldClassName);
            element.addClassName(newClassName);
        }
    },

    // Sets window location
    setLocation: function(top, left) {
        top = this._updateTopConstraint(top);
        left = this._updateLeftConstraint(left);

        var e = this.currentDrag || this.element;
        e.setStyle({top: top + 'px'});
        e.setStyle({left: left + 'px'});

        this.useLeft = true;
        this.useTop = true;
    },

    getLocation: function() {
        var location = {};
        if (this.useTop)
            location = Object.extend(location, {top: this.element.getStyle("top")});
        else
            location = Object.extend(location, {bottom: this.element.getStyle("bottom")});
        if (this.useLeft)
            location = Object.extend(location, {left: this.element.getStyle("left")});
        else
            location = Object.extend(location, {right: this.element.getStyle("right")});

        return location;
    },

    // Gets window size
    getSize: function() {
        return {width: this.width, height: this.height};
    },

    // Sets window size
    setSize: function(width, height, useEffect) {
        width = parseFloat(width);
        height = parseFloat(height);

    // Check min and max size
        if (!this.minimized && width < this.options.minWidth)
            width = this.options.minWidth;

        if (!this.minimized && height < this.options.minHeight)
            height = this.options.minHeight;

        if (this.options. maxHeight && height > this.options. maxHeight)
            height = this.options. maxHeight;

        if (this.options. maxWidth && width > this.options. maxWidth)
            width = this.options. maxWidth;


        if (this.useTop && this.useLeft && Window.hasEffectLib && Effect.ResizeWindow && useEffect) {
            new Effect.ResizeWindow(this, null, null, width, height, {duration: Window.resizeEffectDuration});
        } else {
            this.width = width;
            this.height = height;
            var e = this.currentDrag ? this.currentDrag : this.element;
            if (!this.options.useCSS3) {
                e.setStyle({width: width + this.widthW + this.widthE + "px"});
                e.setStyle({height: height  + this.heightN + this.heightS + "px"});
            }

      // Update content size
            if (!this.currentDrag || this.currentDrag == this.element) {
                var content = $(this.element.id + '_content');
                content.setStyle({height: height  + 'px'});
                content.setStyle({width: width  + 'px'});
            }
        }
    },

    updateHeight: function() {
        this.setSize(this.width, this.content.scrollHeight, true);
    },

    updateWidth: function() {
        this.setSize(this.content.scrollWidth, this.height, true);
    },

    // Brings window to front
    toFront: function() {
        if (this.element.style.zIndex < Windows.maxZIndex)
            this.setZIndex(Windows.maxZIndex + 1);
        if (this.iefix)
            this._fixIEOverlapping();
    },

    getBounds: function(insideOnly) {
        if (! this.width || !this.height || !this.visible)
            this.computeBounds();
        var w = this.width;
        var h = this.height;

        if (!insideOnly) {
            w += this.widthW + this.widthE;
            h += this.heightN + this.heightS;
        }
        var bounds = Object.extend(this.getLocation(), {width: w + "px", height: h + "px"});
        return bounds;
    },

    computeBounds: function() {
        if (! this.width || !this.height) {
            var size = WindowUtilities._computeSize(this.content.innerHTML, this.content.id, this.width, this.height, 0, this.options.className);
            if (this.height)
                this.width = size + 5;
            else
                this.height = size + 5;
        }

        this.setSize(this.width, this.height);
        if (this.centered)
            this._center(this.centerTop, this.centerLeft);
    },

    // Displays window modal state or not
    show: function(modal) {
        this.visible = true;
        if (modal) {
            // Hack for Safari !!
            if (typeof this.overlayOpacity == "undefined") {
                var that = this;
                setTimeout(function() {that.show(modal);}, 10);
                return;
            }
            Windows.addModalWindow(this);

            this.modal = true;
            this.setZIndex(Windows.maxZIndex + 1);
            Windows.unsetOverflow(this);
        }
        else
            if (!this.element.style.zIndex)
                this.setZIndex(Windows.maxZIndex + 1);

    // To restore overflow if need be
        if (this.oldStyle)
            this.getContent().setStyle({overflow: this.oldStyle});

        this.computeBounds();

        this._notify("onBeforeShow");
        if (this.options.useEffects) { // mhollauf mindmeister
          if (this.options.showEffect != Element.show && this.options.showEffectOptions)
              this.options.showEffect(this.element, this.options.showEffectOptions);
          else
              this.options.showEffect(this.element);
        } else {
          Element.show(this.element);
        }

        this._checkIEOverlapping();
        WindowUtilities.focusedWindow = this;
        this._notify("onShow");
    },

    // Displays window modal state or not at the center of the page
    showCenter: function(modal, top, left) {
        this.centered = true;
        this.centerTop = top;
        this.centerLeft = left;

        this.show(modal);
    },

    isVisible: function() {
        return this.visible;
    },

    _center: function(top, left) {
        var windowScroll = WindowUtilities.getWindowScroll(this.options.parent);
        var pageSize = WindowUtilities.getPageSize(this.options.parent);
        if (typeof top == "undefined")
            top = (pageSize.windowHeight - (this.height + this.heightN + this.heightS))/2;
        top += windowScroll.top;

        if (typeof left == "undefined")
            left = (pageSize.windowWidth - (this.width + this.widthW + this.widthE))/2;
        left += windowScroll.left;
        this.setLocation(top, left);
        this.toFront();
    },

    _recenter: function(event) {
        if (this.centered) {
            var pageSize = WindowUtilities.getPageSize(this.options.parent);
            var windowScroll = WindowUtilities.getWindowScroll(this.options.parent);

      // Check for this stupid IE that sends dumb events
            if (this.pageSize && this.pageSize.windowWidth == pageSize.windowWidth && this.pageSize.windowHeight == pageSize.windowHeight &&
                this.windowScroll.left == windowScroll.left && this.windowScroll.top == windowScroll.top)
                return;
            this.pageSize = pageSize;
            this.windowScroll = windowScroll;
      // set height of Overlay to take up whole page and show
            if ($('overlay_modal'))
                $('overlay_modal').setStyle({height: (pageSize.pageHeight + 'px')});

            if (this.options.recenterAuto)
                this._center(this.centerTop, this.centerLeft);
        }
    },

    setNoEffects: function() {
        this.options.useEffects = false;
    },

    // Hides window
    hide: function() {
        this.visible = false;
        if (this.modal) {
            Windows.removeModalWindow(this);
            Windows.resetOverflow();
        }
    // To avoid bug on scrolling bar
        this.oldStyle = this.getContent().getStyle('overflow') || "auto";
        this.getContent().setStyle({overflow: "hidden"});

        if (this.options.useEffects)
            this.options.hideEffect(this.element, this.options.hideEffectOptions);
        else {
            Element.hide(this.element);
            if (this.options.destroyOnClose) this.destroy();
        }

        if(this.iefix)
            this.iefix.hide();

        if (!this.doNotNotifyHide)
            this._notify("onHide");
    },

    close: function() {
        // Asks closeCallback if exists
        if (this.visible) {
            if (this.options.closeCallback && !this.options.closeCallback(this)) return;

            if (this.options.destroyOnClose) {
                var destroyFunc = this.destroy.bind(this);
                if (this.options.hideEffectOptions.afterFinish) {
                    var func = this.options.hideEffectOptions.afterFinish;
                    this.options.hideEffectOptions.afterFinish = function() {func();destroyFunc(); };
                }
                else
                    this.options.hideEffectOptions.afterFinish = function() {destroyFunc(); };
            }
            Windows.updateFocusedWindow();

            this.doNotNotifyHide = true;
            this.hide();
            this.doNotNotifyHide = false;
            this._notify("onClose");
        }
    },

    minimize: function() {
        if (this.resizing)
            return;

        var r2 = $(this.getId() + "_row2");

        if (!this.minimized) {
            this.minimized = true;

            var dh = r2.getDimensions().height;
            this.r2Height = dh;
            var h  = this.element.getHeight() - dh;

            if (this.useLeft && this.useTop && Window.hasEffectLib && Effect.ResizeWindow) {
                new Effect.ResizeWindow(this, null, null, null, this.height -dh, {duration: Window.resizeEffectDuration});
            } else  {
                this.height -= dh;
                this.element.setStyle({height: h + "px"});
                r2.hide();
            }

            if (! this.useTop) {
                var bottom = parseFloat(this.element.getStyle('bottom'));
                this.element.setStyle({bottom: (bottom + dh) + 'px'});
            }
        }
        else {
            this.minimized = false;

            var dh = this.r2Height;
            this.r2Height = null;
            if (this.useLeft && this.useTop && Window.hasEffectLib && Effect.ResizeWindow) {
                new Effect.ResizeWindow(this, null, null, null, this.height + dh, {duration: Window.resizeEffectDuration});
            }
            else {
                var h  = this.element.getHeight() + dh;
                this.height += dh;
                this.element.setStyle({height: h + "px"});
                r2.show();
            }
            if (! this.useTop) {
                var bottom = parseFloat(this.element.getStyle('bottom'));
                this.element.setStyle({bottom: (bottom - dh) + 'px'});
            }
            this.toFront();
        }
        this._notify("onMinimize");

    // Store new location/size if need be
        this._saveCookie();
    },

    maximize: function() {
        if (this.isMinimized() || this.resizing)
            return;

        if (Prototype.Browser.IE && this.heightN == 0)
            this._getWindowBorderSize();

        if (this.storedLocation != null) {
            this._restoreLocation();
            if(this.iefix)
                this.iefix.hide();
        }
        else {
            this._storeLocation();
            Windows.unsetOverflow(this);

            var windowScroll = WindowUtilities.getWindowScroll(this.options.parent);
            var pageSize = WindowUtilities.getPageSize(this.options.parent);
            var left = windowScroll.left;
            var top = windowScroll.top;

            if (this.options.parent != document.body) {
                windowScroll =  {top:0, left:0, bottom:0, right:0};
                var dim = this.options.parent.getDimensions();
                pageSize.windowWidth = dim.width;
                pageSize.windowHeight = dim.height;
                top = 0;
                left = 0;
            }

            if (this.constraint) {
                pageSize.windowWidth -= Math.max(0, this.constraintPad.left) + Math.max(0, this.constraintPad.right);
                pageSize.windowHeight -= Math.max(0, this.constraintPad.top) + Math.max(0, this.constraintPad.bottom);
                left +=  Math.max(0, this.constraintPad.left);
                top +=  Math.max(0, this.constraintPad.top);
            }

            var width = pageSize.windowWidth - this.widthW - this.widthE;
            var height= pageSize.windowHeight - this.heightN - this.heightS;

            if (this.useLeft && this.useTop && Window.hasEffectLib && Effect.ResizeWindow) {
                new Effect.ResizeWindow(this, top, left, width, height, {duration: Window.resizeEffectDuration});
            }
            else {
                this.setSize(width, height);
                this.element.setStyle(this.useLeft ? {left: left} : {right: left});
                this.element.setStyle(this.useTop ? {top: top} : {bottom: top});
            }

            this.toFront();
            if (this.iefix)
                this._fixIEOverlapping();
        }
        this._notify("onMaximize");

    // Store new location/size if need be
        this._saveCookie();
    },

    isMinimized: function() {
        return this.minimized;
    },

    isMaximized: function() {
        return (this.storedLocation != null);
    },

    setOpacity: function(opacity) {
        if (Element.setOpacity)
            Element.setOpacity(this.element, opacity);
    },

    setZIndex: function(zindex) {
        this.element.setStyle({zIndex: zindex});
        Windows.updateZindex(zindex, this);
    },

    setTitle: function(newTitle) {
        if (!newTitle || newTitle == "")
            newTitle = "&nbsp;";

        Element.update(this.element.id + '_top', newTitle);
    },

    getTitle: function() {
        return $(this.element.id + '_top').innerHTML;
    },

    setStatusBar: function(element) {
        var statusBar = $(this.getId() + "_bottom");

        if (typeof(element) == "object") {
            if (this.bottombar.firstChild)
                this.bottombar.replaceChild(element, this.bottombar.firstChild);
            else
                this.bottombar.appendChild(element);
        }
        else
            this.bottombar.innerHTML = element;
    },

    _checkIEOverlapping: function() {
        if(!this.iefix && (navigator.appVersion.indexOf('MSIE')>0) && (navigator.userAgent.indexOf('Opera')<0) && (this.element.getStyle('position')=='absolute')) {
            new Insertion.After(this.element.id, '<iframe id="' + this.element.id + '_iefix" '+ 'style="display:none;position:absolute;filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);" ' + 'src="javascript:false;" frameborder="0" scrolling="no"></iframe>');
            this.iefix = $(this.element.id+'_iefix');
        }
        if(this.iefix)
            setTimeout(this._fixIEOverlapping.bind(this), 50);
    },

    _fixIEOverlapping: function() {
        Position.clone(this.element, this.iefix);
        this.iefix.style.zIndex = this.element.style.zIndex - 1;
        this.iefix.show();
    },

    _getWindowBorderSize: function(event) {
        // Hack to get real window border size!!
        var div = this._createHiddenDiv(this.options.className + "_n");
        this.heightN = Element.getDimensions(div).height;
        div.parentNode.removeChild(div);

        var div = this._createHiddenDiv(this.options.className + "_s");
        this.heightS = Element.getDimensions(div).height;
        div.parentNode.removeChild(div);

        var div = this._createHiddenDiv(this.options.className + "_e");
        this.widthE = Element.getDimensions(div).width;
        div.parentNode.removeChild(div);

        var div = this._createHiddenDiv(this.options.className + "_w");
        this.widthW = Element.getDimensions(div).width;
        div.parentNode.removeChild(div);

        var div = document.createElement("div");
        div.className = "overlay_" + this.options.className ;
        document.body.appendChild(div);
    //alert("no timeout:\nopacity: " + div.getStyle("opacity") + "\nwidth: " + document.defaultView.getComputedStyle(div, null).width);
        var that = this;

    // Workaround for Safari!!
        setTimeout(function() {that.overlayOpacity = ($(div).getStyle("opacity")); div.parentNode.removeChild(div);}, 10);

    // Workaround for IE!!
        if (Prototype.Browser.IE && !this.options.useCSS3) {
            this.heightS = $(this.getId() +"_row3").getDimensions().height;
            this.heightN = $(this.getId() +"_row1").getDimensions().height;
        }

    // Safari size fix
        if (Prototype.Browser.WebKit && Prototype.Browser.WebKitVersion < 420)
            this.setSize(this.width, this.height);
        if (this.doMaximize)
            this.maximize();
        if (this.doMinimize)
            this.minimize();
    },

    _createHiddenDiv: function(className) {
        var objBody = document.body;
        var win = document.createElement("div");
        win.setAttribute('id', this.element.id+ "_tmp");
        win.className = className;
        win.style.display = 'none';
        win.innerHTML = '';
        objBody.insertBefore(win, objBody.firstChild);
        return win;
    },

    _storeLocation: function() {
        if (this.storedLocation == null) {
            this.storedLocation = {useTop: this.useTop, useLeft: this.useLeft,
                top: this.element.getStyle('top'), bottom: this.element.getStyle('bottom'),
                left: this.element.getStyle('left'), right: this.element.getStyle('right'),
                width: this.width, height: this.height };
        }
    },

    _restoreLocation: function() {
        if (this.storedLocation != null) {
            this.useLeft = this.storedLocation.useLeft;
            this.useTop = this.storedLocation.useTop;

            if (this.useLeft && this.useTop && Window.hasEffectLib && Effect.ResizeWindow)
                new Effect.ResizeWindow(this, this.storedLocation.top, this.storedLocation.left, this.storedLocation.width, this.storedLocation.height, {duration: Window.resizeEffectDuration});
            else {
                this.element.setStyle(this.useLeft ? {left: this.storedLocation.left} : {right: this.storedLocation.right});
                this.element.setStyle(this.useTop ? {top: this.storedLocation.top} : {bottom: this.storedLocation.bottom});
                this.setSize(this.storedLocation.width, this.storedLocation.height);
            }

            Windows.resetOverflow();
            this._removeStoreLocation();
        }
    },

    _removeStoreLocation: function() {
        this.storedLocation = null;
    },

    _saveCookie: function() {
        if (this.cookie) {
            var value = "";
            if (this.useLeft)
                value += "l:" +  (this.storedLocation ? this.storedLocation.left : this.element.getStyle('left'));
            else
                value += "r:" + (this.storedLocation ? this.storedLocation.right : this.element.getStyle('right'));
            if (this.useTop)
                value += ",t:" + (this.storedLocation ? this.storedLocation.top : this.element.getStyle('top'));
            else
                value += ",b:" + (this.storedLocation ? this.storedLocation.bottom :this.element.getStyle('bottom'));

            value += "," + (this.storedLocation ? this.storedLocation.width : this.width);
            value += "," + (this.storedLocation ? this.storedLocation.height : this.height);
            value += "," + this.isMinimized();
            value += "," + this.isMaximized();
            WindowUtilities.setCookie(value, this.cookie);
        }
    },

    _createWiredElement: function() {
        if (! this.wiredElement) {
            if (Prototype.Browser.IE)
                this._getWindowBorderSize();
            var div = document.createElement("div");
            div.className = "wired_frame " + this.options.className + "_wired_frame";

            div.style.position = 'absolute';
            this.options.parent.insertBefore(div, this.options.parent.firstChild);
            this.wiredElement = $(div);
        }
        if (this.useLeft)
            this.wiredElement.setStyle({left: this.element.getStyle('left')});
        else
            this.wiredElement.setStyle({right: this.element.getStyle('right')});

        if (this.useTop)
            this.wiredElement.setStyle({top: this.element.getStyle('top')});
        else
            this.wiredElement.setStyle({bottom: this.element.getStyle('bottom')});

        var dim = this.element.getDimensions();
        this.wiredElement.setStyle({width: dim.width + "px", height: dim.height +"px"});

        this.wiredElement.setStyle({zIndex: Windows.maxZIndex+30});
        return this.wiredElement;
    },

    _hideWiredElement: function() {
        if (! this.wiredElement || ! this.currentDrag)
            return;
        if (this.currentDrag == this.element)
            this.currentDrag = null;
        else {
            if (this.useLeft)
                this.element.setStyle({left: this.currentDrag.getStyle('left')});
            else
                this.element.setStyle({right: this.currentDrag.getStyle('right')});

            if (this.useTop)
                this.element.setStyle({top: this.currentDrag.getStyle('top')});
            else
                this.element.setStyle({bottom: this.currentDrag.getStyle('bottom')});

            this.currentDrag.hide();
            this.currentDrag = null;
            if (this.doResize)
                this.setSize(this.width, this.height);
        }
    },

    _notify: function(eventName) {
        if (this.options[eventName])
            this.options[eventName](this);
        Windows.notify(eventName, this); // mhollauf mindmeister - always do both!
    }
};

// Windows containers, register all page windows
var Windows = {
    windows: [],
    modalWindows: [],
    observers: [],
    focusedWindow: null,
    maxZIndex: 0,
    overlayShowEffectOptions: {duration: 0.5},
    overlayHideEffectOptions: {duration: 0.5},

    addObserver: function(observer) {
        this.removeObserver(observer);
        this.observers.push(observer);
    },

    removeObserver: function(observer) {
        this.observers = this.observers.reject( function(o) { return o==observer; });
    },

    // onDestroy onStartResize onStartMove onResize onMove onEndResize onEndMove onFocus onBlur onBeforeShow onShow onHide onMinimize onMaximize onClose
    notify: function(eventName, win) {
        this.observers.each( function(o) {if(o[eventName]) o[eventName](eventName, win);});
    },

    // Gets window from its id
    getWindow: function(id) {
        return this.windows.detect(function(d) { return d.getId() ==id; });
    },

    // Gets the last focused window
    getFocusedWindow: function() {
        return this.focusedWindow;
    },

    updateFocusedWindow: function() {
        this.focusedWindow = this.windows.length >=2 ? this.windows[this.windows.length-2] : null;
    },

    // Registers a new window (called by Windows constructor)
    register: function(win) {
        this.windows.push(win);
    },

    // Add a modal window in the stack
    addModalWindow: function(win) {
        // Disable screen if first modal window
        if (this.modalWindows.length == 0) {
            WindowUtilities.disableScreen(win.options.className, 'overlay_modal', win.overlayOpacity, win.getId(), win.options.parent);
        }
        else {
            // Move overlay over all windows
            if (Window.keepMultiModalWindow) {
                $('overlay_modal').style.zIndex = Windows.maxZIndex + 1;
                Windows.maxZIndex += 1;
                WindowUtilities._hideSelect(this.modalWindows.last().getId());
            }
                // Hide current modal window
            else
                this.modalWindows.last().element.hide();
      // Fucking IE select issue
            WindowUtilities._showSelect(win.getId());
        }
        this.modalWindows.push(win);
    },

    removeModalWindow: function(win) {
        this.modalWindows.pop();

    // No more modal windows
        if (this.modalWindows.length == 0)
            WindowUtilities.enableScreen();
        else {
            if (Window.keepMultiModalWindow) {
                this.modalWindows.last().toFront();
                WindowUtilities._showSelect(this.modalWindows.last().getId());
            }
            else
                this.modalWindows.last().element.show();
        }
    },

    // Registers a new window (called by Windows constructor)
    register: function(win) {
        this.windows.push(win);
    },

    // Unregisters a window (called by Windows destructor)
    unregister: function(win) {
        this.windows = this.windows.reject(function(d) { return d==win; });
    },

    // Closes all windows
    closeAll: function() {
        this.windows.each( function(w) {Windows.close(w.getId());} );
    },

    closeAllModalWindows: function() {
        WindowUtilities.enableScreen();
        this.modalWindows.each( function(win) {if (win) win.close();});
    },

    // Minimizes a window with its id
    minimize: function(id, event) {
        var win = this.getWindow(id);
        if (win && win.visible)
            win.minimize();
        Event.stop(event);
    },

    // Maximizes a window with its id
    maximize: function(id, event) {
        var win = this.getWindow(id);
        if (win && win.visible)
            win.maximize();
        Event.stop(event);
    },

    // Closes a window with its id
    close: function(id, event) {
        var win = this.getWindow(id);
        if (win)
            win.close();
        if (event)
            Event.stop(event);
    },

    blur: function(id) {
        var win = this.getWindow(id);
        if (!win)
            return;
        if (win.options.blurClassName)
            win.changeClassName(win.options.blurClassName);
        if (this.focusedWindow == win)
            this.focusedWindow = null;
        win._notify("onBlur");
    },

    focus: function(id) {
        var win = this.getWindow(id);
        if (!win)
            return;
        if (this.focusedWindow)
            this.blur(this.focusedWindow.getId());

        if (win.options.focusClassName)
            win.changeClassName(win.options.focusClassName);
        this.focusedWindow = win;
        win._notify("onFocus");
    },

    unsetOverflow: function(except) {
        this.windows.each(function(d) { d.oldOverflow = d.getContent().getStyle("overflow") || "auto" ; d.getContent().setStyle({overflow: "hidden"}); });
        if (except && except.oldOverflow)
            except.getContent().setStyle({overflow: except.oldOverflow});
    },

    resetOverflow: function() {
        this.windows.each(function(d) { if (d.oldOverflow) d.getContent().setStyle({overflow: d.oldOverflow}); });
    },

    updateZindex: function(zindex, win) {
        if (zindex > this.maxZIndex) {
            this.maxZIndex = zindex;
            if (this.focusedWindow)
                this.blur(this.focusedWindow.getId());
        }
        this.focusedWindow = win;
        if (this.focusedWindow)
            this.focus(this.focusedWindow.getId());
    }
};

var Dialog = {
    dialogId: null,
    onCompleteFunc: null,
    callFunc: null,
    parameters: null,

    confirm: function(content, parameters) {
        // Get Ajax return before
        if (content && typeof content != "string") {
            Dialog._runAjaxRequest(content, parameters, Dialog.confirm);
            return;
        }
        content = content || "";

        parameters = parameters || {};
        var okLabel = parameters.okLabel ? parameters.okLabel : 'js_ok'.tr();
        var cancelLabel = parameters.cancelLabel ? parameters.cancelLabel : 'js_cancel'.tr();

    // Backward compatibility
        parameters = Object.extend(parameters, parameters.windowParameters || {});
        parameters.windowParameters = parameters.windowParameters || {};

        parameters.className = parameters.className || "alert";
        var okButtonClass = "class ='" + (parameters.buttonClass ? parameters.buttonClass + " " : "") + " ok_button'";
        var cancelButtonClass = "class ='" + (parameters.buttonClass ? parameters.buttonClass + " " : "") + " cancel_button'";
        var content = "\
      <div class='" + parameters.className + "_message'>" + content  + "</div>\
        <div class='" + parameters.className + "_buttons'>\
          <input type='button' value='" + okLabel + "' onclick='Dialog.okCallback()' " + okButtonClass + "/>\
          <input type='button' value='" + cancelLabel + "' onclick='Dialog.cancelCallback()' " + cancelButtonClass + "/>\
        </div>\
    ";
        return this._openDialog(content, parameters);
    },

    confirmOrCancel: function(content, parameters) {
        parameters = parameters || {};
        parameters.windowParameters = parameters.windowParameters || {};
        parameters = Object.extend(parameters, parameters.windowParameters);

        var okLabel = parameters.okLabel ? parameters.okLabel : "OK";
        var cancelLabel = parameters.cancelLabel ? parameters.cancelLabel : "Cancel";
        var noLabel = parameters.noLabel ? parameters.noLabel : "No";

        parameters.className = parameters.className || "alert";
        var okButtonClass = "class ='" + (parameters.buttonClass ? parameters.buttonClass + " " : "") + " ok_button'";
        var cancelButtonClass = "class ='" + (parameters.buttonClass ? parameters.buttonClass + " " : "") + " cancel_button'";
        var noButtonClass = "class ='" + (parameters.buttonClass ? parameters.buttonClass + " " : "") + " no_button'";

        var content = "\
            <div class='" + parameters.className + "_message'>" + content  + "</div>\
            <div class='" + parameters.className + "_buttons'>\
                <input type='button' value='" + okLabel + "' onclick='Dialog.okCallback()' " + okButtonClass + "/>\
                <input type='button' value='" + noLabel + "' onclick='Dialog.noCallback()' " + noButtonClass + "/>\
                <input type='button' value='" + cancelLabel + "' onclick='Dialog.cancelCallback()' " + cancelButtonClass + "/>\
            </div>\
        ";
        return this._openDialog(content, parameters);
    },

    alert: function(content, parameters) {
        // Get Ajax return before
        if (content && typeof content != "string") {
            Dialog._runAjaxRequest(content, parameters, Dialog.alert);
            return;
        }
        content = content || "";

        parameters = parameters || {};
        var okLabel = parameters.okLabel ? parameters.okLabel : "Ok";

    // Backward compatibility
        parameters = Object.extend(parameters, parameters.windowParameters || {});
        parameters.windowParameters = parameters.windowParameters || {};

        parameters.className = parameters.className || "alert";

        var okButtonClass = "class ='" + (parameters.buttonClass ? parameters.buttonClass + " " : "") + " ok_button'";
        var content = "\
      <div class='" + parameters.className + "_message'>" + content  + "</div>\
        <div class='" + parameters.className + "_buttons'>\
          <input type='button' value='" + okLabel + "' onclick='Dialog.okCallback()' " + okButtonClass + "/>\
        </div>";
        return this._openDialog(content, parameters);
    },

    info: function(content, parameters) {
        // Get Ajax return before
        if (content && typeof content != "string") {
            Dialog._runAjaxRequest(content, parameters, Dialog.info);
            return;
        }
        content = content || "";

    // Backward compatibility
        parameters = parameters || {};
        parameters = Object.extend(parameters, parameters.windowParameters || {});
        parameters.windowParameters = parameters.windowParameters || {};

        parameters.className = parameters.className || "alert";

        var content = "<div id='modal_dialog_message' class='" + parameters.className + "_message'>" + content  + "</div>";
        if (parameters.showProgress)
            content += "<div id='modal_dialog_progress' class='" + parameters.className + "_progress'>  </div>";

        parameters.ok = null;
        parameters.cancel = null;

        return this._openDialog(content, parameters);
    },

    setInfoMessage: function(message) {
        $('modal_dialog_message').update(message);
    },

    closeInfo: function() {
        Windows.close(this.dialogId);
    },

    _openDialog: function(content, parameters) {
        var className = parameters.className;

        if (! parameters.height && ! parameters.width) {
            parameters.width = WindowUtilities.getPageSize(parameters.options.parent || document.body).pageWidth / 2;
        }
        if (parameters.id)
            this.dialogId = parameters.id;
        else {
            var t = new Date();
            this.dialogId = 'modal_dialog_' + t.getTime();
            parameters.id = this.dialogId;
        }

    // compute height or width if need be
        if (! parameters.height || ! parameters.width) {
            var size = WindowUtilities._computeSize(content, this.dialogId, parameters.width, parameters.height, 5, className);
            if (parameters.height)
                parameters.width = size + 5;
            else
                parameters.height = size + 5;
        }
        parameters.effectOptions = parameters.effectOptions;
        parameters.resizable   = parameters.resizable || false;
        parameters.minimizable = parameters.minimizable || false;
        parameters.maximizable = parameters.maximizable ||  false;
        parameters.draggable   = parameters.draggable || false;
        parameters.closable    = parameters.closable || false;

        var win = new Window(parameters);
        win.getContent().innerHTML = content;

        win.showCenter(true, parameters.top, parameters.left);
        win.setDestroyOnClose();

        win.cancelCallback = parameters.onCancel || parameters.cancel;
        win.okCallback = parameters.onOk || parameters.ok;
        win.noCallback = parameters.onNo || parameters.no;

        return win;
    },

    _getAjaxContent: function(originalRequest)  {
        Dialog.callFunc(originalRequest.responseText, Dialog.parameters);
    },

    _runAjaxRequest: function(message, parameters, callFunc) {
        if (message.options == null)
            message.options = {};
        Dialog.onCompleteFunc = message.options.onComplete;
        Dialog.parameters = parameters;
        Dialog.callFunc = callFunc;

        message.options.onComplete = Dialog._getAjaxContent;
        new Ajax.Request(message.url, message.options);
    },

    okCallback: function() {
        var win = Windows.focusedWindow || Windows.getFocusedWindow();
        if (!win.okCallback || win.okCallback(win)) {
            // Remove onclick on button
            $$("#" + win.getId()+" input").each(function(element) {element.onclick=null;});
            win.close();
        }
    },

    noCallback: function() {
        var win = Windows.focusedWindow || Windows.getFocusedWindow();
        if (!win.noCallback || win.noCallback(win)) {
            // Remove onclick on button
            $$("#" + win.getId()+" input").each(function(element) {element.onclick=null;});
            win.close();
        }
    },

    cancelCallback: function() {
        var win = Windows.focusedWindow;
        // Remove onclick on button
        $$("#" + win.getId()+" input").each(function(element) {element.onclick=null;});
        win.close();
        if (win.cancelCallback)
            win.cancelCallback(win);
    }
};
/*
  Based on Lightbox JS: Fullsize Image Overlays
  by Lokesh Dhakar - http://www.huddletogether.com

  For more information on this script, visit:
  http://huddletogether.com/projects/lightbox/

  Licensed under the Creative Commons Attribution 2.5 License - http://creativecommons.org/licenses/by/2.5/
  (basically, do anything you want, just leave my name and link)
*/

if (Prototype.Browser.WebKit) {
    var array = navigator.userAgent.match(new RegExp(/AppleWebKit\/([\d\.\+]*)/));
    Prototype.Browser.WebKitVersion = parseFloat(array[1]);
}

var WindowUtilities = {
    // From dragdrop.js
    getWindowScroll: function(parent) {
        var T, L, W, H;
        parent = parent || document.body;
        if (parent != document.body) {
            T = parent.scrollTop;
            L = parent.scrollLeft;
            W = parent.scrollWidth;
            H = parent.scrollHeight;
        }
        else {
            var w = window;
            with (w.document) {
                if (w.document.documentElement && documentElement.scrollTop) {
                    T = documentElement.scrollTop;
                    L = documentElement.scrollLeft;
                } else if (w.document.body) {
                    T = body.scrollTop;
                    L = body.scrollLeft;
                }
                if (w.innerWidth) {
                    W = w.innerWidth;
                    H = w.innerHeight;
                } else if (w.document.documentElement && documentElement.clientWidth) {
                    W = documentElement.clientWidth;
                    H = documentElement.clientHeight;
                } else {
                    W = body.offsetWidth;
                    H = body.offsetHeight;
                }
            }
        }
        return { top: T, left: L, width: W, height: H };
    },
    //
    // getPageSize()
    // Returns array with page width, height and window width, height
    // Core code from - quirksmode.org
    // Edit for Firefox by pHaez
    //
    getPageSize: function(parent){
        parent = parent || document.body;
        var windowWidth, windowHeight;
        var pageHeight, pageWidth;
        if (parent != document.body) {
            windowWidth = parent.getWidth();
            windowHeight = parent.getHeight();
            pageWidth = parent.scrollWidth;
            pageHeight = parent.scrollHeight;
        }
        else {
            var xScroll, yScroll;

            if (window.innerHeight && window.scrollMaxY) {
                xScroll = document.body.scrollWidth;
                yScroll = window.innerHeight + window.scrollMaxY;
            } else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
                xScroll = document.body.scrollWidth;
                yScroll = document.body.scrollHeight;
            } else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
                xScroll = document.body.offsetWidth;
                yScroll = document.body.offsetHeight;
            }


            if (self.innerHeight) {  // all except Explorer
                windowWidth = self.innerWidth;
                windowHeight = self.innerHeight;
            } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
                windowWidth = document.documentElement.clientWidth;
                windowHeight = document.documentElement.clientHeight;
            } else if (document.body) { // other Explorers
                windowWidth = document.body.clientWidth;
                windowHeight = document.body.clientHeight;
            }

      // for small pages with total height less then height of the viewport
            if(yScroll < windowHeight){
                pageHeight = windowHeight;
            } else {
                pageHeight = yScroll;
            }

      // for small pages with total width less then width of the viewport
            if(xScroll < windowWidth){
                pageWidth = windowWidth;
            } else {
                pageWidth = xScroll;
            }
        }
        return {pageWidth: pageWidth ,pageHeight: pageHeight , windowWidth: windowWidth, windowHeight: windowHeight};
    },

    disableScreen: function(className, overlayId, overlayOpacity, contentId, parent) {
        WindowUtilities.initLightbox(overlayId, className, function() {this._disableScreen(className, overlayId, overlayOpacity, contentId);}.bind(this), parent || document.body);
    },

    _disableScreen: function(className, overlayId, overlayOpacity, contentId) {
        // prep objects
        var objOverlay = $(overlayId);

        var pageSize = WindowUtilities.getPageSize(objOverlay.parentNode);

    // Hide select boxes as they will 'peek' through the image in IE, store old value
        if (contentId && Prototype.Browser.IE) {
            WindowUtilities._hideSelect();
            WindowUtilities._showSelect(contentId);
        }

    // set height of Overlay to take up whole page and show
        objOverlay.style.height = (pageSize.pageHeight + 'px');
        objOverlay.style.display = 'none';
        var win = Windows.getFocusedWindow(); // mindmeister mhollauf
        if (overlayId == "overlay_modal" && Window.hasEffectLib && Windows.overlayShowEffectOptions && win && win.options.useEffects) {
            objOverlay.overlayOpacity = overlayOpacity;
            new Effect.Appear(objOverlay, Object.extend({from: 0, to: overlayOpacity}, Windows.overlayShowEffectOptions));
        }
        else
            objOverlay.style.display = "block";
    },

    enableScreen: function(id) {
        id = id || 'overlay_modal';
        var objOverlay =  $(id);
        if (objOverlay) {
            // hide lightbox and overlay
            if (id == "overlay_modal" && Window.hasEffectLib && Windows.overlayHideEffectOptions)
                new Effect.Fade(objOverlay, Object.extend({from: objOverlay.overlayOpacity, to:0}, Windows.overlayHideEffectOptions));
            else {
                objOverlay.style.display = 'none';
                objOverlay.parentNode.removeChild(objOverlay);
            }

      // make select boxes visible using old value
            if (id != "__invisible__")
                WindowUtilities._showSelect();
        }
    },

    _hideSelect: function(id) {
        if (Prototype.Browser.IE) {
            id = id ==  null ? "" : "#" + id + " ";
            $$(id + 'select').each(function(element) {
                if (! WindowUtilities.isDefined(element.oldVisibility)) {
                    element.oldVisibility = element.style.visibility ? element.style.visibility : "visible";
                    element.style.visibility = "hidden";
                }
            });
        }
    },

    _showSelect: function(id) {
        if (Prototype.Browser.IE) {
            id = id ==  null ? "" : "#" + id + " ";
            $$(id + 'select').each(function(element) {
                if (WindowUtilities.isDefined(element.oldVisibility)) {
                    // Why?? Ask IE
                    try {
                        element.style.visibility = element.oldVisibility;
                    } catch(e) {
                        element.style.visibility = "visible";
                    }
                    element.oldVisibility = null;
                }
                else {
                    if (element.style.visibility)
                        element.style.visibility = "visible";
                }
            });
        }
    },

    isDefined: function(object) {
        return typeof(object) != "undefined" && object != null;
    },

    // initLightbox()
    // Function runs on window load, going through link tags looking for rel="lightbox".
    // These links receive onclick events that enable the lightbox display for their targets.
    // The function also inserts html markup at the top of the page which will be used as a
    // container for the overlay pattern and the inline image.
    initLightbox: function(id, className, doneHandler, parent) {
        // Already done, just update zIndex
        if ($(id)) {
            Element.setStyle(id, {zIndex: Windows.maxZIndex + 1});
            Windows.maxZIndex++;
            doneHandler();
        }
            // create overlay div and hardcode some functional styles (aesthetic styles are in CSS file)
        else {
            var objOverlay = document.createElement("div");
            objOverlay.setAttribute('id', id);
            objOverlay.className = "overlay_" + className;
            objOverlay.style.display = 'none';
            objOverlay.style.position = 'absolute';
            objOverlay.style.top = '0';
            objOverlay.style.left = '0';
            objOverlay.style.zIndex = Windows.maxZIndex + 1;
            Windows.maxZIndex++;
            objOverlay.style.width = '100%';
            parent.insertBefore(objOverlay, parent.firstChild);
            if (Prototype.Browser.WebKit && id == "overlay_modal") {
                setTimeout(function() {doneHandler();}, 10);
            }
            else
                doneHandler();
        }
    },

    setCookie: function(value, parameters) {
        document.cookie= parameters[0] + "=" + escape(value) +
                         ((parameters[1]) ? "; expires=" + parameters[1].toGMTString() : "") +
                         ((parameters[2]) ? "; path=" + parameters[2] : "") +
                         ((parameters[3]) ? "; domain=" + parameters[3] : "") +
                         ((parameters[4]) ? "; secure" : "");
    },

    getCookie: function(name) {
        var dc = document.cookie;
        var prefix = name + "=";
        var begin = dc.indexOf("; " + prefix);
        if (begin == -1) {
            begin = dc.indexOf(prefix);
            if (begin != 0) return null;
        } else {
            begin += 2;
        }
        var end = document.cookie.indexOf(";", begin);
        if (end == -1) {
            end = dc.length;
        }
        return unescape(dc.substring(begin + prefix.length, end));
    },

    _computeSize: function(content, id, width, height, margin, className) {
        var objBody = document.body;
        var tmpObj = document.createElement("div");
        tmpObj.setAttribute('id', id);
        tmpObj.className = className + "_content";

        if (height)
            tmpObj.style.height = height + "px";
        else
            tmpObj.style.width = width + "px";

        tmpObj.style.position = 'absolute';
        tmpObj.style.top = '0';
        tmpObj.style.left = '0';
        tmpObj.style.display = 'none';

        tmpObj.innerHTML = content;
        objBody.insertBefore(tmpObj, objBody.firstChild);

        var size;
        if (height)
            size = $(tmpObj).getDimensions().width + margin;
        else
            size = $(tmpObj).getDimensions().height + margin;
        objBody.removeChild(tmpObj);
        return size;
    }
};

eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)d[e(c)]=k[c]||e(c);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('8 1j(J){7 1t.72(J)}71={70:8(L){L.Q.R=(L.Q.R==\'19\')?\'\':\'19\'},6Z:8(L){7 L.Q.R!=\'19\'}};8 47(h,Q){9 P=h.Q[Q];e(!P||P==\'4c\'){9 33=(1t.4d?1t.4d.6Y(h,z):z);P=33?33[Q]:z}e(Q==\'6X\')7 P?3Y(P):1.0;7 P==\'4c\'?z:P}8 2o(h){9 2o=[];e(!h.32())7[];9 1E=h.2C;15(9 i=0,1v=1E.F;i<1v;i++){9 L=1E[i];e(L.4a==1)2o.1V(L)}7 2o}8 6W(h){e(h.Q.R==\'19\')7 1k;9 1F=h.4b;2k(1F&&1F!=1t){e(1F.Q.R==\'19\')7 1k;1F=1F.4b}7 1K}8 49(h,1C){9 1S=[];e(!h.32())7[];9 1E=h.2C;15(9 i=0,1v=1E.F;i<1v;i++){9 L=1E[i];e(L.4a==1&&1C&&1C.6V(z,L))1S.1V(L);e(L.32())1S=1S.6U(49(L,1C))}7 1S}8 6T(h){9 R=47(h,\'R\');e(R!=\'19\'&&R!=z)7{43:h.6S,41:h.6R};9 14=h.Q;9 44=14.30;9 31=14.1y;9 45=14.R;14.30=\'6Q\';e(31!=\'6P\')14.1y=\'6O\';14.R=\'6N\';9 42=h.6M;9 40=h.6L;14.R=45;14.1y=31;14.30=44;7{43:42,41:40}}8 3d(h){h.Q.R=\'19\'}8 3f(h){h.Q.R=\'\'}6K.6J={6I:8(1D){7 4.2Z(3Z.6H,1D)||4.2Z(3Z.6G,1D)},2Z:8(2Y,1D){9 1d=2Y.2F(1D);e(1d==-1)7;7 3Y(2Y.6F(1d+1D.F+1))}};1n.1m(6E.2b,{6D:8(h,1d){e(1d>4.F){4[4.F]=h;7 4.F-1}15(9 i=4.F;i>1d;i--)4[i]=4[i-1];4[1d]=h;7 1d},6C:8(h){15(9 i=0;i<4.F;i++){e(h.2y){e(h.2y(4[i]))K}u{e(4[i]==h)K}}e(i==4.F)7;15(9 j=i;j<4.F-1;j++)4[j]=4[j+1];4.6B()},U:8(1C){9 U=0;9 i=4.F;2k(i>0){e(1C(4[--i]))U++}7 U},2n:8(){9 2n=0;9 i=4.F;2k(i>0){2n+=4[--i]}7 2n}});1n.1m(3Q.2b,{6A:8(){2R(4.V()){G\'1K\':7 1K;G\'1k\':7 1k;3R:7 z}},6z:8(){7 4.2g(/6y?:\\/\\/[^\\s<]*/,8(1B){7"<a 3X=\\""+1B+"\\" 2a=\\"3W\\">"+1B+"</a>"}).2g(/(?:^|\\s)6x\\.[^\\s<]*/,8(1B){7"<a 3X=\\"6w://"+1B+"\\" 2a=\\"3W\\">"+1B+"</a>"})},6v:8(){9 3V=/<(?:.|\\s)*?>/g;7 4.3p(3V,"")},6u:8(){7 4.1P(\'\\r\').3b(8(L){7 L.6t()}).2A(\'\\r\').2g(/&6s;/,\'"\')},6r:8(3T){e(4.3P(0)==\'#\')9 2m=4.2G(1,6);u 9 2m=4;9 v=2m.3U(/^(\\w{2})(\\w{2})(\\w{2})$/);e(v){r=Z(v[1],16);g=Z(v[2],16);b=Z(v[3],16)}u{v=2m.3U(/^(\\w{1})(\\w{1})(\\w{1})$/);e(v){r=Z(v[1]+v[1],16);g=Z(v[2]+v[2],16);b=Z(v[3]+v[3],16)}u 7 z}e(3T)7[r,g,b];u 7"6q("+r+","+g+","+b+")"},o:8(1R){9 2l=6p[4];e(1R){9 v=2l.1P(\'%\');9 1Q=v[0];9 3S=/^([6o])(.*)$/;15(9 i=1;i<v.F;i++){p=3S.6n(v[i]);e(!p||!1R[i-1])6m;e(p[1]==\'d\'){1Q+=Z(1R[i-1],10)}u e(p[1]==\'s\'){1Q+=1R[i-1]}1Q+=p[2]}2l=1Q}7 2l},6l:8(){2R(2Q.2P.3w){G"%Y-%m-%D":9 v=4.1P(\'-\');9 1A=v[1]+"/"+v[2]+"/"+v[0];7 1A.V();G"%m/%D/%Y":7 4.V();G"%D.%m.%Y":9 v=4.1P(\'.\');9 1A=v[1]+"/"+v[0]+"/"+v[2];7 1A.V();G"%D-%m-%Y":9 v=4.1P(\'-\');9 1A=v[1]+"/"+v[0]+"/"+v[2];7 1A.V();3R:7 4.V()}}});3Q.6k=8(a,b){8 2X(t){9 2j=[],x=0,y=-1,n=0,i,j;2k(i=(j=t.3P(x++)).6j(0)){9 m=(i==46||(i>=48&&i<=57));e(m!==n){2j[++y]="";n=m}2j[y]+=j}7 2j}9 1q=2X(a);9 1p=2X(b);15(x=0;1q[x]&&1p[x];x++){e(1q[x]!==1p[x]){9 c=2J(1q[x]),d=2J(1p[x]);e(c==1q[x]&&d==1p[x]){7 c-d}u 7(1q[x]>1p[x])?1:-1}}7 1q.F-1p.F};1n.1m(6i,{6h:8(h){h=$(h);h.2h=h.P;7 h.2U(\'6g\',8(){e(h.2h!=h.P)7;h.6f(\'2W\').P=\'\'}).2U(\'6e\',8(){e(h.P.6d()!=\'\')7;h.3O(\'2W\').P=h.2h}).3O(\'2W\')},6c:8(2i){2i=$(2i);2i.6b().2t(8(L){e(L.2h==L.P)L.P=\'\'})},6a:8(3N,3M,2V){9 1c=1t.69[3N].68[3M];e(1c.F==2H)e(1c.3L)7 1c.P;u 7"";15(9 i=0;i<1c.F;i++){e(1c[i].3L){e(1c[i].J==\'67\'){e(1j(2V))7 1j(2V).P;u 7""}u{7 1c[i].P}}}7""}});1n.1m(1M,{66:8(O){9 1b=0;e(!O)O=3J.O;e(O.3K){1b=O.3K/65;e(3J.64)1b=-1b}u e(O.3I)1b=-O.3I/3;7(1b>0)?E.63(1b):E.62(1b);},61:8(h,2T,3H){9 2S=8(){1M.60(h,2T,2S);3H.5Z(h,5Y)};1M.2U(h,2T,2S);7 h},5X:8(O){7 5W?[O.3G[0].3F,O.3G[0].3E]:[O.3F,O.3E]}});1n.1m(1o.2b,{3u:8(3C){9 2e=4.5V(),2f=4.5U();9 18=4.5T(),3y=4.5S();8 1a(3D){7 3D.5R(2)};7 3C.2g(/\\%([5Q])/,8(3B){2R(3B[1]){G\'a\':7[\'5P\'.o(),\'5O\'.o(),\'5N\'.o(),\'5M\'.o(),\'5L\'.o(),\'5K\'.o(),\'5J\'.o()][2e];K;G\'A\':7[\'5I\'.o(),\'5H\'.o(),\'5G\'.o(),\'5F\'.o(),\'5E\'.o(),\'5D\'.o(),\'5C\'.o()][2e];K;G\'b\':7[\'5B\'.o(),\'5A\'.o(),\'5z\'.o(),\'5y\'.o(),\'3A\'.o(),\'5x\'.o(),\'5w\'.o(),\'5v\'.o(),\'5u\'.o(),\'5t\'.o(),\'5s\'.o(),\'5r\'.o()][2f];K;G\'B\':7[\'5q\'.o(),\'5p\'.o(),\'5o\'.o(),\'5n\'.o(),\'3A\'.o(),\'5m\'.o(),\'5l\'.o(),\'5k\'.o(),\'5j\'.o(),\'5i\'.o(),\'5h\'.o(),\'5g\'.o()][2f];K;G\'c\':7 4.V();K;G\'d\':7 4.3z();K;G\'D\':7 1a(4.3z());K;G\'H\':7 1a(18);K;G\'i\':7(18===12||18===0)?12:(18+12)%12;K;G\'I\':7 1a((18===12||18===0)?12:(18+12)%12);K;G\'m\':7 1a(2f+1);K;G\'M\':7 1a(3y);K;G\'p\':7 18>11?\'5f\':\'5e\';K;G\'S\':7 1a(4.5d());K;G\'w\':7 2e;K;G\'y\':7 1a(4.3x()%5c);K;G\'Y\':7 4.3x().V();K}}.2q(4))},5b:8(3v){9 2N=2Q.2P.3w;e(!3v){e(2Q.2P.5a==0){9 2O=" %i:%M %p"}u{9 2O=" %H:%M"}2N+=2O}7 4.3u(2N)}});1o.59=8(2d){9 1z=q 1o(2d);7 q 1o(1z-(1z.3t()*3s))};1o.58=8(2d){9 1z=q 1o(2d);7 q 1o(1z.56()+(1z.3t()*3s))};1n.1m(E,{55:8(C){7(C[0]>0)?((C[1]>0)?1:0):((C[1]>0)?2:3)},54:8(n,2L,2M){e(n>2M)7 2M;u e(n<2L)7 2L;u 7 n},53:8(1y){9 x=1y[0],y=-1y[1],1O=E.52(y/x);e(x<0&&y>=0)1O+=+E.2c;u e(x<0&&y<0)1O+=E.2c;u e(x>0&&y<0)1O+=2*E.2c;7 51/E.2c*1O},1r:8(n){7 n*n},50:8(2K){7 Z(E.2v(E.1r(2K[0])+E.1r(2K[1])))},29:8(1x,1w){7[1x[0]+1w[0],1x[1]+1w[1]]},4Z:8(1x,1w){7[1x[0]-1w[0],1x[1]-1w[1]]}});1n.1m(2J.2b,{4Y:8(2I){e(4<3r)7 4+" 4X";u e(4<(4W))7 E.3q(4/3r,2I)+" 4V";u 7 E.3q(4/4U,2I)+" 4T"},4S:8(c,d,t){9 n=4,c=4R(c=E.1I(c))?2:c,d=d==2H?",":d,t=t==2H?".":t,s=n<0?"-":"",i=Z(n=E.1I(+n||0).3o(c))+"",j=(j=i.F)>3?j%3:0;7 s+(j?i.2G(0,j)+t:"")+i.2G(j).3p(/(\\d{3})(?=\\d)/g,"$1"+t)+(c?d+E.1I(n-i).3o(c).4Q(2):"")}});1s.4P({3m:8(1N,T){1N.Q.4O=T[0]+"3n";1N.Q.4N=T[1]+"3n"},4M:8(1N,2a,29){9 T=4L.4K(2a);T[0]+=29[0];T[1]+=29[1];1s.3m(1N,T);7 T},3i:8(J,17,1l,2E){e(1L J==\'2D\')9 W=1j(J);u 9 W=J;e(!W)7;e(27){W.3l=8(){e(W.1u.2F(\'3k\')!=-1)7 1k;17(O);e(!!2E)1M.3j(O);7!!1l}}u{W.3l=8(O){e(W.1u.2F(\'3k\')!=-1)7 1k;17(O);e(!!2E)1M.3j(O);7!!1l}}},4J:8(J,17,1l){e(1L J==\'2D\')9 W=1j(J);u 9 W=J;W.4I=8(){17();7!!1l}},4H:8(J,17,1l){e(1L J==\'2D\')9 W=1j(J);u 9 W=J;W.4G=8(){17();7!!1l}},4F:8(J,17){4.3i(J,17,1k,1K)},4E:8(J,1u){e(!27)7 $(J).4D(\'.\'+1u);9 28=$(J).2C;15(9 i=0,1v=28.F;i<1v;i++)e(28[i].1u==1u)7 $(28[i])}});1s.4C=8(h){9 1i=1j(\'3h\');e(!1i){1i=q 1s(\'4B\',{J:\'3h\'});1i.Q.R=\'19\';1t.4A.3g(1i)}1i.3g(h);1i.4z=\'\'};25.4y=8(h,24,26){e(3e||(27&&26))1s.3f(h);u 7 q 25.4x(h,24)};25.4w=8(h,24,26){e(3e||(27&&26))1s.3d(h);u 7 q 25.4v(h,24)};9 4u=20.1Z({2B:z,13:z,1Y:8(3c){4.2B=3c||10;4.13=[]},4t:8(23){4.13.1V(23);e(4.13.F>4.2B)4.13.1X()},2A:8(){7 4.13.3b(8(h){7 h.V()}).2A(\'\\n\\n\')},4s:8(){7 4.13.F==0},4r:8(){7 4.13[0]},1X:8(){7 4.13.1X()},3a:8(23){7 4.13.3a(23)},1e:8(){7 4.13.F}});9 N=20.1Z({x:z,y:z,1Y:8(x,y){4.x=(1L x==\'39\')?x:x[0];4.y=(1L x==\'39\')?y:x[1]},34:8(C){7 q N(4.x+C.x,4.y+C.y)},4q:8(C){7 q N(4.x-C.x,4.y-C.y)},1W:8(2z){4.x+=2z.x;4.y+=2z.y},4p:8(){7[4.x,4.y]},2y:8(p){7(4.x==p.x&&4.y==p.y)},V:8(){7[4.x,4.y].V()}});9 1f=20.1Z({f:z,k:z,1Y:8(f,m){4.f=f;e(m 4o N){4.k=m}u{4.k=(m==z)?q N(4.f.x,4.f.y-1):q N(4.f.x+1,4.f.y+m)}},F:8(){7 E.2v(E.1r(4.f.x-4.k.x)+E.1r(4.f.y-4.k.y))},21:8(){e(4.k.x==4.f.x)7 z;7(4.k.y-4.f.y)/(4.k.x-4.f.x)},1H:8(l){9 1h=4.21(),1g=l.21(),x,y;e(1h==1g)7 z;e(1h!=z)e(1g!=z){x=(1h*4.f.x-1g*l.f.x-4.f.y+l.f.y)/(1h-1g);y=1h*(x-4.f.x)+4.f.y}u{x=l.f.x;y=1h*(l.f.x-4.f.x)+4.f.y}u e(1g!=z){x=4.f.x;y=1g*(4.f.x-l.f.x)+l.f.y}u{7 z}7 q N(x,y)},4n:8(f,k){9 2x=q 1f(f,k),2w=4.1H(2x);7(2w)?2x.1G(2w):1K},1G:8(p){7([4.f.x,4.k.x].38()>=p.x-0.22&&p.x>=[4.f.x,4.k.x].37()-0.22&&[4.f.y,4.k.y].38()>=p.y-0.22&&p.y>=[4.f.y,4.k.y].37()-0.22)},V:8(){7[4.f,4.k].V()},4m:8(f,d){9 m=4.21();e(m==z)7[q N(f.x,f.y-d),q N(f.x,f.y+d)];9 1J=d/E.2v(1+E.1r(m));7[q N(f.x+1J,f.y+(m*1J)),q N(f.x-1J,f.y-(m*1J))]}});9 2p=20.1Z({f:z,k:z,1Y:8(f,k){4.f=f||q N(0,0);4.k=k||q N(0,0)},1e:8(){7 q N(E.1I(4.k.x-4.f.x),E.1I(4.k.y-4.f.y))},35:8(){7 q N(Z((4.k.x+4.f.x)/ 2), Z((4.k.y + 4.f.y) /2))},4l:8(C){4.k=4.1e();4.k.1W(C);4.f=C},1X:8(C){4.f.1W(C);4.k.1W(C)},2r:8(C){7(C.x>=4.f.x&&C.x<=4.k.x&&C.y>=4.f.y&&C.y<=4.k.y)},1H:8(36){9 2u=[];4.2s().2t(8(s){e(!(p=36.1H(s)))7;e(s.1G(p))2u.1V(p)});7 2u},4k:8(C){9 l=q 1f(C,4.35()),p=z;4.2s().2t(8(s){e(!(p=l.1H(s)))7;e(l.1G(p)&&s.1G(p))4j $K});7 p},1U:8(){7[q N(4.f.x,4.f.y),q N(4.k.x,4.f.y),q N(4.k.x,4.k.y),q N(4.f.x,4.k.y)]},2s:8(){9 c=4.1U();7[q 1f(c[0],c[1]),q 1f(c[1],c[2]),q 1f(c[2],c[3]),q 1f(c[3],c[0])]},4i:8(X){7!(4.k.x<X.f.x||4.f.x>X.k.x||4.k.y<X.f.y||4.f.y>X.k.y)},4h:8(X){9 U=4.1U().U(8(1T){7 X.2r(1T)}.2q(4));e(U!=0){7 U}u{U=X.1U().U(8(1T){7 4.2r(1T)}.2q(4));e(!U){9 f=4.T,4g=4.1e,k=X.T,4f=X.1e;9 r=X;U=(((4.f.x<r.f.x&&4.k.x>r.k.x)&&(4.f.y>r.f.y&&4.k.y<r.k.y))||((4.f.x>r.f.x&&4.k.x<r.k.x)&&(4.f.y<r.f.y&&4.k.y>r.k.y)))?5:0}7 U}}});2p.4e=8(T,1e){7 q 2p(T,T.34(1e))};',62,437,'||||this|||return|function|var|||||if|p1||element|||p2||||tr||new||||else|bits||||null|||point||Math|length|case|||id|break|el||Point|event|value|style|display||pos|count|toString|elem|otherRectangle||parseInt||||_contents|els|for||callback|hours|none|pad|delta|radioObj|index|size|Line|m2|m1|garbageBin|_|false|return_value|extend|Object|Date|bb|aa|sqr|Element|document|className|len|arr2|arr1|position|utc|res|link|iterator|searchString|children|parent|isOnSegment|intersection|abs|tmp|true|typeof|Event|source|rad|split|out|subst|desc|coord|coords|push|doOffset|shift|initialize|create|Class|slope|02|object|options|Effect|all|isIE|ch|offset|target|prototype|PI|strDate|day|month|gsub|_default|form|tz|while|text|col|sum|childElements|Rectangle|bind|within|sides|each|points|sqrt|intersPoint|tmpL|equals|off|join|_capacity|childNodes|string|nobubble|indexOf|substr|undefined|precision|Number|rectSize|lower|upper|user_date_format|user_hour_format|user|ServerData|switch|wrapper|eventName|observe|selectId|inactive|chunkify|dataString|_search|visibility|originalPosition|hasChildNodes|css|getOffsetWith|center|line|min|max|number|unshift|collect|capacity|hide|isIE6|show|appendChild|IELeakGarbageBin|bindOnClick|stop|disabled|onclick|moveTo|px|toFixed|replace|round|1024|60000|getTimezoneOffset|strftime|only_date|date_format|getUTCFullYear|minutes|getUTCDate|js_may|part|format|num|clientY|clientX|targetTouches|handler|detail|window|wheelDelta|checked|radioId|formId|addClassName|charAt|String|default|re|return_array|match|reTag|new_page|href|parseFloat|navigator|originalHeight|height|originalWidth|width|originalVisibility|originalDisplay||getStyle||descendants|nodeType|parentNode|auto|defaultView|fromPosSize|s2|s1|overlap_type|overlaps|throw|radialIntersection|move|pointsAtDistance|onOppositeSides|instanceof|toArray|getOffsetFrom|first|empty|add|Buffer|Fade|FadeIE|Appear|AppearIE|innerHTML|body|div|discard|down|safeSelect|bindOnClickNoBubble|onmouseout|bindOnUnhover|onmouseover|bindOnHover|getElementOffset|canvas|moveToElement|top|left|addMethods|slice|isNaN|formatMoney|MB|1048576|KB|1048100|Bytes|toHumanSize|negativeOffset|diagonal|180|atan|slopeAngle|constrain|getQuadrant|getTime||toUTC|fromUTC|hour_format|toUIString|100|getUTCSeconds|AM|PM|js_december|js_november|js_octomber|js_september|js_august|js_july|js_june|js_april|js_march|js_february|js_january|js_december_short|js_november_short|js_octomber_short|js_september_short|js_august_short|js_july_short|js_june_short|js_april_short|js_march_short|js_february_short|js_january_short|js_saturday|js_friday|js_thursday|js_wednesday|js_tuesday|js_monday|js_sunday|js_saturday_short|js_friday_short|js_thursday_short|js_wednesday_short|js_tuesday_short|js_monday_short|js_sunday_short|aAbBcdDHiImMpSwyY|toPaddedString|getMinutes|getHours|getMonth|getDay|isTouchDevice|getTouchsafeCoordinates|arguments|apply|stopObserving|observeOnce|floor|ceil|opera|120|wheel|radio_image_format|elements|forms|getRadioSelectValue|getElements|clearHints|strip|blur|removeClassName|focus|defaultHint|Form|charCodeAt|naturalCompare|toDate|continue|exec|ds|i18n|rgb|parseRGB|quot|unescapeHTML|safeUnescapeHTML|stripHTML|http|www|https|toLink|toBoolean|pop|remove|insert|Array|substring|appVersion|userAgent|get|BrowserVersion|Prototype|clientHeight|clientWidth|block|absolute|fixed|hidden|offsetHeight|offsetWidth|getDimensions|concat|call|isVisible|opacity|getComputedStyle|visible|toggle|_Element|getElementById'.split('|'),0,{}))
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)d[e(c)]=k[c]||e(c);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('k m={7c:{},S:{},2G:{},Y:{},2Q:{},4L:{},3o:{},O:{}};m.7c={bF:g(){},79:g(){31{bE"bD"}2Z(e){o e.7b||e.7a}},78:g(1q,1U,2U,2T,2S){k 3G=\'\',bt=(1q)?1q.7b||1q.7a:u.79();j(1U)3G+=1U;j(1q)3G+=(1q.Q?1q.Q.bC():\'\')+" - "+1q.1U;k 4L={1U:3G,bB:bt,2U:2U};6u.6t.bA(4L);j(2T)2M(2T);j(2S)2S();j(1J.5d)2M(1U)},bz:g(1q,1U,2T,2S){1J.2H.by=11;k 2U=X.bx(bw)+"\\n---\\n"+1J.bv.5u();u.78(1q,1U,2U,2T,2S)}};m.Y={bu:g(){},bs:g(){o 1f.2A+\'//\'+T.bq+(1f.77?\':\'+1f.77:\'\')},bp:g(){V.1f.bo()},3d:g(Q,15,4K){k t=M 2C;t.bn(t.2B()+((4K||1)*8.bm));T.74=Q+\'=\'+bl(15)+\'; 4K=\'+t.bk()},4b:g(Q){k 76=M bj(Q+\'=([^;]*);?\',\'bi\'),r=76.bh(T.74)||[];o(r.17>1)?bg(r[1]):11},6f:g(Z,1o,1H){k q=Z.46(\'/\').5w();j(T.bf(q)){j(1o)1o();o}k s=T.2Y("be");s.1G("q",q);s.1G("1C","24/bd");s.1G("1X",Z);T.72("71")[0].1W(s);j(1o){j(3S){j(1H){k 3F=39(g(){k 1p=B;31{1p=1H.1p()}2Z(e){}j(1p){3Y(3F);1o.1p()}},2l)}H{s.2X(\'5r\',1o)}}H j(2e){j(1H){s.4J=g(){j(u.1Z==\'37\'||u.1Z==\'38\'){k 3F=39(g(){k 1p=B;31{1p=1H.1p()}2Z(e){}j(1p){3Y(3F);1o.1p()}},2l)}}}H{s.4J=g(){j(u.1Z==\'37\'||u.1Z==\'38\'){1o()}}}}H{s.O=g(){1o()}}j(!3X&&!2e)s.4J=g(){j(u.1Z==\'37\'||u.1Z==\'38\')1o()}}},6g:g(73){73.3L(g(Q){k l=T.2Y("bc");l.1G("1C","24/47");l.1G("bb","ba");l.1G("1g",Q);l.1G("b9","b8");T.72("71")[0].1W(l)})},54:g(1m,q,2R,I,K){k 3E=\'\';1m=V.1f.2A+"//"+V.1f.51+\'/50/\'+1m+\'.b7\';j(2e){k 2A=1f.1g.W(/^6Z/i)?\'6Z://\':\'6P://\';3E+=\'<4H b6="b5:b4-b3-b2-b1-b0" aZ="\'+2A+\'6E.6O.6N/aY/3U/aX/3T/aW.aV#aU=9,0,0,0" I="\'+I+\'" K="\'+K+\'" q="\'+q+\'" 1N="6T"><1E Q="6S" 15="6R" />\'+\'<1E Q="6Q" 15="B" /><1E Q="aT" 15="\'+1m+\'" /><1E Q="3M" 15="B" />\'+\'<1E Q="6Y" 15="B" /><1E Q="6X" 15="6W" /><1E Q="6V" 15="#6U" />\'+\'<1E Q="2R" 15="\'+2R+\'"/><1E Q="6M" 15="6L"/></4H>\'}H{3E+=\'<4W q="\'+q+\'" 1X="\'+1m+\'" 3M="B" 6Y="B" 6X="6W" 6V="#6U" I="\'+I+\'" K="\'+K+\'" Q="\'+q+\'" 1N="6T" 6S="6R" 6Q="B" \'+\'1C="32/x-3U-3T" aS="6P://aR.6O.6N/aQ/aP" 2R="\'+2R+\'" 6M="6L" />\'}o 3E},3P:g(){j(G.2Q!=11&&G.2Q.17>0){j(G.2Q["6K 6J 2.0"]||G.2Q["6K 6J"]){o J}}o B},aO:g(){j(T.3C.4D.15==\'\'){P.2o(\'6I\',\'aN\')}H{C(\'6I\').z.2n="2g"}j(T.3C.4I.15==\'\'){P.2o(\'4I\',\'aM\')}H{C(\'4I\').z.2n="2g"}},aL:g(6H){j(C(\'1h\')&&1h)1h.4r();C(6H).z.2n="2g"},27:11,6F:g(){j(u.27!=11)o u.27;j((G.1d.19(\'2D\')!=-1)||(G.1d.19(\'3W\')!=-1))o u.27=J;j(2e){u.27=aK()}H{k 3D=G.2z["32/x-6G"];j(G.1d.19(\'aJ\')!=-1&&!3D)3D=G.2z.aI(\'32/x-6G\');u.27=(1j(3D)=="4H")}o u.27},aH:g(2P){2P=2P||5n.aG[2];j(!2P||2P.17<=0){2M(\'aF\'.N());o B}H j(!u.6F()){k E=M 1z(\'6E\',X.13(m.S.12(),{16:\'\',K:3B,I:3n}));E.1y(\'/4u/aE\');E.1x();E.1w(1);o B}H{o J}},6D:g(2O){j(1J.2H.aD)o aC+2O;H o 2O},aB:g(2O){o"Z("+u.6D(2O)+")"}};m.Y.23={4E:g(1c,14,2E,2N,1D){1B.1A();j(1j 1h!="1t"&&1h!=11){1h.4r()}k Z=\'/1i/\'+1c+\'6C/\';k 4G=(1c.19(\'C\')>0)?1c.43(0,1c.19(\'C\')):1c;j(1D){j(1D.1c)4G=1D.1c;j(1D.4C)Z=\'/\'+1D.4C+\'/\'+1c+\'6C/\'}j(1n&&1n.1T!="3t")Z=\'/\'+1n.1T+Z;k E=M 1z(4G,X.13(m.S.12(),1D||{16:\'\',K:4s}));E.1y(Z+(14?14:\'\')+"?"+(2E?\'aA=J\'+(1e?\'&2J=\'+1e:\'\'):\'\')+(2N?2N:\'\'));E.1x();E.1w(1);o B},az:g(1c,R){3V.3A(1c,R);j(1j 1h!="1t"&&1h!=11){1h.ay()}},6e:g(1c,14,2E,2N,1D){o u.4E(1c,14,2E,2N,X.13(1D||{},{22:"1i",3m:J}))},ax:g(1C){j($(\'3C\')){k E=M 1z(\'6B\',X.13(m.S.12(),{I:4p,K:3B,22:"1i",3m:J,6A:B}));E.aw(\'3C\');E.1x();E.1w(1);j(1C)k 4F="1r.av(\'"+1C+"\')";H k 4F="$(\'4D\').au()";[\'at\',\'as\',\'ar\',\'aq\'].3L(g(l){m.S.5S(\'ap\'+l)});6b(4F,2l);o B}H{o u.4E(\'4D\',11,11,1C?\'1C=\'+1C:11,{I:4p,K:3B,4C:"2I",1c:"6B",22:"1i",3m:J,6A:B})}},ao:g(R){k D=R.D();j(!D.4B(\'an\')&&!D.4B(\'am\')&&!D.4B(\'ak\')&&$(\'6z\')){$(\'6z\').4k()}},aj:g(q){k E=M 1z(\'ai\',X.13(m.S.12(),{K:3n,16:\'\',I:60}));k 3s=(1n.1T==\'3t\')?\'/1i/6y\':\'/\'+1n.1T+\'/1i/6y\';E.1y(3s);E.1x();E.1w(1);o B},ah:g(14){1B.1A();k E=M 1z(\'ag\',X.13(m.S.12(),{16:\'\',K:af,I:5Z}));j(m.3o.64){E.1y(\'/ae/6w/\',{ad:{6x:1J.2H.6x}})}H{E.1y(\'/3x/6w/\'+14)}E.1x();E.1w(1)},ac:g(q){1B.1A();k E=M 1z(\'ab\',X.13(m.S.12(),{16:\'\',K:aa,I:a9}));E.1y(\'/3x/a8/\'+q);E.1x();E.1w(1)},a7:g(q){1B.1A();1S.2L(\'<1b z="2v-4A:4z%; 2v-4y: 4x;">\'+\'a6\'.N()+\'</1b><p z="4w-1r: 0;">\'+\'a5\'.N()+\'</p>\',{2K:g(){u.3A();m.Y.23.6v(q)},1R:"1Q",1P:X.13(m.S.12(),{K:a4,I:3B,4v:\'2u\',1v:\'1k 2u\'})})},6v:g(q){6u.6t.a3(q)},a2:g(q,26){1S.2L(\'<1b z="2v-4A:4z%; 2v-4y: 4x;">\'+\'a1\'.N()+\'</1b><p z="4w-1r: 0;">\'+\'a0\'.N()+\'</p>\',{2K:g(){u.3A();m.Y.23.6s(26)},9Z:g(){m.Y.23.6r(q)},1R:"1Q",1P:X.13(m.S.12(),{K:6p,I:6o,4v:\'2u\',1v:\'1k 2u\'})})},6s:g(26){M 2t.2s(\'/2I/9Y?\'+X.9X({\'9W[26]\':26}),{2r:J})},6r:g(q){M 2t.2s(\'/2I/9V/\'+q,{2r:J})},9U:g(18,3z,6q,Q,1e){j(6q==\'9T\'){k 24=\'9S\'.N()}H{k 24=\'9R\'.N([Q,Q])}1S.2L(\'<1b z="2v-4A:4z%; 2v-4y: 4x;">\'+\'9Q\'.N()+\'</1b><p z="4w-1r: 0;">\'+24+\'</p>\',{2K:g(){u.3A();m.Y.23.6n(18,3z,1e)},1R:"1Q",1P:X.13(m.S.12(),{K:6p,I:6o,4v:\'2u\',1v:\'1k 2u\'})})},6n:g(18,3z,1e){M 2t.2s(\'/1i/9P?14=\'+18+\'&9O=\'+3z+\'&2J=\'+1e,{2r:J,3u:J})},9N:g(26){k 24=\'9M\'.N([26]);1S.2M(\'<1b>\'+\'9L\'.N()+\'</1b><p>\'+24+\'</p>\',{1v:\'1k\',1R:"1Q",1P:X.13(m.S.12(),{I:3r,K:9K})})},9J:g(){j(m.3o.4n())o;k 3y=M 1z(\'9I\',X.13(m.S.12(),{16:\'\',K:4s}));3y.1y(\'/4u/9H/\');3y.1x();3y.1w(1)},9G:g(14){k 18=3w.3v(14);1B.1A();V.1f.1g="/3x/3l/"+18.4t;o B},9F:g(14){k 18=3w.3v(14);1B.1A();1S.2M(\'<1b>\'+\'9E\'.N()+\' "\'+18.16+\'"</1b><1a 2i="9D"><a 1g="/3x/3l/\'+18.4t+\'"><3I 1X="/4u/9C/\'+18.4t+\'?9B=1" K="9A" 9z="0"></a></1a>\',{1v:\'1k\',1R:"1Q",1P:X.13(m.S.12(),{I:3n})})},9y:g(14){k 18=3w.3v(14);1B.1A();1S.2L(\'<1b>\'+\'9x\'.N()+\'</1b><p>\'+\'9w\'.N()+\' "\'+18.16+\'".<br/>\'+\'6m\'.N()+\'</p>\',{1v:\'1k\',2K:m.Y.23.6j.29(11,18.q),1R:"1Q",1P:X.13(m.S.12(),{K:6l,I:6k})})},9v:g(14){k 18=3w.3v(14);1B.1A();1S.2L(\'<1b>\'+\'9u\'.N()+\'</1b><p>\'+\'9t\'.N()+\' "\'+18.16+\'".<br/>\'+\'6m\'.N()+\'</p>\',{1v:\'1k\',2K:m.Y.23.6i.29(11,18.q),1R:"1Q",1P:X.13(m.S.12(),{K:6l,I:6k})})},6j:g(q){M 2t.2s(\'/1i/5s/\'+q+(1e?"?2J="+1e:""),{2r:J,3u:J});o J},6i:g(q){M 2t.2s(\'/1i/9s/\'+q+(1e?"?2J="+1e:""),{2r:J,3u:J});o J},9r:g(14){1B.1A();M 2t.2s(\'/1i/9q/\'+14+(1e?"?2J="+1e:""),{2r:J,3u:J});o J},9p:g(){k Z=\'/1i/9o\';j(1n&&1n.1T!="3t")Z=\'/\'+1n.1T+Z;k E=M 1z(X.13(m.S.12(),{q:\'9n\',16:\'\',K:4s,6h:B}));E.1y(Z);E.1x();E.1w(1)},9m:g(){j(1j 1h!=\'1t\'){1h.4r()}k E=M 1z(\'4q\',X.13(m.S.12(),{16:\'\',K:3r,I:3r,6h:B}));k 3s=(1n.1T==\'3t\')?\'/2I/4q\':\'/\'+1n.1T+\'/2I/4q\';E.1y(3s);E.1x();E.1w(1)},9l:g(6d){m.Y.6g([1J.2H.9k]);m.Y.6f(1J.2H.9j,g(){6c.6e(11,11,6d)},g(){o 1j 6c!="1t"})},69:11,9i:g(){1S.9h(\'<1a q="9g" 1N="9f"><3f z="I:70%">\'+\'9e\'.N()+\'</3f><1a q="9d"><1a 2i="9c"><1a q="3q" z="I: 1%"></1a></1a></1a></1a>\',{1v:\'1k\',1R:"1Q",1P:X.13(m.S.12(),{I:3r,K:2l,5Y:{},9b:P.3l,9a:P.4k})});6b(u.6a.29(u),4p)},6a:g(){C("3q").z.I="99";C("3q").z.68="98";k 2q=0;k 3p=\'4o\';u.69=V.39(g(){C("3q").z.68=2q+"1F";97(3p){67\'4o\':2q+=10;42;67\'66\':2q-=10;42}j(2q>96)3p=\'66\';j(2q<10)3p=\'4o\'},2l)}};m.3o={4n:g(){o(1j 4m!=\'1t\'&&4m.95())},65:g(){o(!u.4n()||4m.Y.65())},64:B,94:B};m.S={93:g(q,2G){P.2F(q,\'63\');j(C(q).15==2G)C(q).15=\'\'},92:g(q,2G){j(C(q).15!=\'\')o;P.2o(q,\'63\');C(q).15=2G},91:g(){k L=$A(20);k i=L.17;2p(i>0){C(L[--i]).1u=B;P.2F(L[i],\'1u\')}},90:g(){k L=$A(20);k i=L.17;2p(i>0){C(L[--i]).1u=J;P.2o(L[i],\'1u\')}},8Z:g(q){o(C(q).22.19(\'1u\')==-1)},8Y:g(){k L=$A(20);k i=L.17;2p(i>0){P.2F(L[--i],\'1u\')}},8X:g(){k L=$A(20);k i=L.17;2p(i>0){P.2o(L[--i],\'1u\')}},8W:g(q){o(C(q).22.19(\'4l\')!=-1)},8V:g(){k L=$A(20);k i=L.17;2p(i>0){P.2F(L[--i],\'4l\');j(2d&&C(L[i])&&L[i].19(\'62\')<0)C(L[i]).z.61=\'#8U\'}},8T:g(){k L=$A(20);k i=L.17;2p(i>0){P.2o(L[--i],\'4l\');j(2d&&C(L[i])&&L[i].19(\'62\')<0)C(L[i]).z.61=\'#8S\'}},12:g(){o{1v:"1k",22:\'8R\',K:3n,I:60,59:8Q,3m:5f,8P:B,8O:B,8N:B,8M:V.8L>5Z,5Y:{8K:0.5},8J:{8I:g(){T.2a.z.8H=\'\'}}}},8G:g(1O){1O=C(\'8F\'+1O);j(1O&&1O.2x.17>0){1O.22="8E";P.3l(1O)}H P.4k(1O)},8D:g(F,2m,4j,5X){k D=C(F);D.z.2n="Z("+2m+((2d||5X)?".8C?"+4i.4h+")":".4g?"+4i.4h+")");D.z.8B=4j[0]+"1F "+4j[1]+"1F";D.z.8A=\'8z-8y\'},8x:g(F,2m){C(F).z.2n="Z("+2m+".4g?"+4i.4h+")"},8w:g(F,2m){C(F).z.2n="Z("+2m+".4g)";C(F).z.5C="Z(\'/5B/5A/5z/47/5y.5x\')"},8v:g(1l){j(3X)o P.2F(1l.3i,"8u");1l.3i.1m=1l;1l.3i.8t=g(e){j(1j e==\'1t\')e=V.R;k 3k=5W.4Q(e),3j=5W.4P(e),21=P.8s(u);j(2e&&(3j<21[1]||3j>=21[1]+u.4f||3k<21[0]||3k>=21[0]+u.5V))o;k x=3k-21[0],y=3j-21[1],w=u.1m.5V,h=u.1m.4f;u.1m.z.1r=y-(h/2)-u.4f+\'1F\';u.1m.z.28=x-(w-30)+\'1F\';C(\'5U\').z.5T="8r"};1l.3i.8q=g(e){C(\'5U\').z.5T="2g"}},8p:g(1l){k D=C(1l);j(D){D.1u=J;D.z.3h=0.3;D.z.5R="5Q(3h=30)";j(D.1g){D.4e=D.1g;D.1g="#"}j(D.2h){D.4d=D.2h;D.2h=11}}},5S:g(1l){k D=C(1l);j(D){D.1u=B;D.z.3h=1;D.z.5R="5Q(3h=2l)";j(D.4e){D.1g=D.4e}j(D.4d){D.2h=D.4d}}},5F:g(v){k F=$(\'v\');j(!v)v=F.5G;k 2k=v.2k||25;j(v.1N=="28"||v.1N=="5L")P.5P(F,v.2j,{5O:v.1r||-30,5M:(v.1N=="28")?-(F.4c+2k):($(v.2j).4c+2k),5K:B,5J:B});H P.5P(F,v.2j,{5O:(v.1N=="8o")?-(F.5N+2k):($(v.2j).5N+2k),5M:v.28||(v.3g=="5L"?-F.4c+75:-30),5K:B,5J:B})},8n:g(v){j($(\'v\')||!$(v.2j)||$(v.2j).z.8m==\'2g\'||2d)o;j(v.q&&m.Y.4b(\'5E\'+v.q))o;j(!v.8l){k 5I=5v(m.Y.4b(\'5D\')||0);j(5I>((M 2C()).2B()-8k))o;}k F=M P("1a",{\'q\':\'v\',\'2i\':v.1N,\'z\':v.I?\'I:\'+v.I+\'1F\':\'\'}).2E(\'<3N 2i="3g \'+(v.3g?v.3g:\'\')+\'"></3N><3f>\'+v.16+\'</3f><p>\'+v.8j+\'</p>\'+\'<5H><3e><a 1g="#" 2i="1k" 2h="P.8i(\\\'v\\\');o B">\'+\'8h\'.N()+\'</a></3e>\'+(v.4a?\'<3e><a 1g="#" 2i="8g" 2h="o m.S.8f(\\\'\'+v.4a[0]+\'\\\', \\\'\'+v.4a[1]+\'\\\')">\'+\'8e\'.N()+\'</a></3e>\':\'\')+\'</5H>\');T.2a.1W(F);F.5G=v;m.S.5F(v);j(v.q)m.Y.3d(\'5E\'+v.q,1,49);H m.Y.3d(\'8d\',8c.19(v)+1,49);m.Y.3d("5D",(M 2C()).2B(),49)}};m.1M={8b:g(F,U,8a){C(F).z.89=\'2g\';k 1X=48.88(U).Z;k 1s=M P(\'3I\');1s.1X=1X;1s.I=48.87;1s.K=48.86;j(2d)1s.z.5C="Z(\'/5B/5A/5z/47/5y.5x\')";$(F).3Z({\'1r\':1s})},85:g(U){j(!U)o\'84\'.N();U=U.46(\'/\').5w();j(U.19("5t")>=0){k l=5v(U.45(5)-1);j(l==0)o\'83\'.N();j(l==4)o\'82\'.N();o l*25+"% "+\'81\'.N()}j(U.19("40")>=0)o\'80\'.N([U.45(10)]);k 1M=U.46(\'C\');k 44=[];3b(k i=0,3c=1M.17;i<3c;i++){44.3a(1M[i].45(0).7Z()+1M[i].43(1))}o 44.5u(\' \')},7Y:g(U){o(7X.19(U)>-1)?U.43(0,U.19("C")):11},7W:g(1M){k 41={},1L=[],2f;3b(k i=0,3c=1M.17;i<3c;i++){k U=1M[i];j(2f=U.W(/(.*)(C)(.*)/)){j(41[2f[1]])42;41[2f[1]]=J;j(2f[1]==\'5t\')1L.3Z(U,((1L[0]&&1L[0].W(\'40\'))?1:0));H j(2f[1]==\'40\')1L.3Z(U,0);H 1L.3a(U)}H 1L.3a(U);}o 1L}};m.O={O:g(){j(m.O.35){o}m.O.35=J;3b(k x=0,al=m.O.f.17;x<al;x++){m.O.f[x]()}},7V:g(){k a=20;3b(k x=0,al=a.17;x<al;x++){j(1j a[x]===\'g\'){j(m.O.35){a[x]()}H{m.O.f.3a(a[x])}}}},5o:g(){j(/7U|7T/i.1H(G.1d)){m.O.34=39(g(){j(/38|37/.1H(T.1Z)){3Y(m.O.34);5s m.O.34;m.O.O()}},10)}H j(T.36){T.36(\'7S\',m.O.O,B)}H j(!m.O.5p){j(V.36){V.36(\'5r\',m.O.O,B)}H j(V.5q){o V.5q(\'O\',m.O.O)}}},f:[],35:B,34:11,5p:B};m.O.5o();j(!V.Q)V.Q="7R";k 5n={};k 5c=G.1d.W(/2b/)=="2b";k 7Q=G.1d.W(/2b\\/2/)=="2b/2";k 3S=G.1d.W(/2D/)=="2D";k 3X=G.1d.W(/3W/)=="3W";k 5e=G.1d.W(/5m/)=="5m";k 2e=G.1K.W(/2c/)=="2c";k 2d=G.1K.W(/2c 6.0/)=="2c 6.0";k 7P=G.1K.W(/2c 7.0/)=="2c 7.0";k 7O=G.1K.W(/5l/)=="5l";k 7N=G.1K.W(/3V/)=="3V";k 7M=G.1d.W(/5k/)=="5k";k 5i=G.1K.W(/5j/)=="5j";k 5h=G.1K.W(/33/)=="33";k 5g=G.1K.W(/33/)=="33";k 7L=5i||5h||5g;k 3P=G.2z["32/x-3U-3T"];k 5f=(G.1d.W(/2b\\/3.6/)=="2b/3.6")||5e||(3S&&!G.1d.W(/7K\\/2/));g d(1Y){j(!1J.5d)o;j(5c&&1j 1I!="1t")1I.5b(1Y);H j(G.1d.W(/2D/)=="2D"&&V.1I){V.1I.5b(1Y)}H{j(!C(\'3R\')){k F=M P(\'1a\',{q:\'3R\'}).7J({7I:\'7H\',1r:\'5a\',28:\'5a\',I:\'7G\',K:\'7F\',59:\'7E\',7D:\'#7C\',7B:\'58\',7A:\'58\',7z:\'7y\'});T.2a.1W(F)}C(\'3R\').2x+=1Y+\'<br/>\'}}g 57(1Y,3Q){j(V.1f.1g.19("57")<0)o;j(1j 1I!="1t"&&1I.56){j(3Q)1I.7x(1Y);H 1I.56(1Y)}H{j(3Q)d(((M 2C()).2B()-55)+\' 7w\');H 55=(M 2C()).2B()}}k 53=(g(){k a=B;k b="7v";o{"52":g(){j(a)o;a=J;j(!m.Y.3P())o;k c=T.2Y("1a");c.q="7u";T.2a.1W(c);31{c.2x=m.Y.54("7t",b,"7s="+b,1,1);V[b]=d}2Z(7r){}},"7q":g(d,f){53.52();d=V.1f.2A+"//"+V.1f.51+\'/50/\'+d;k e=T[b]||V[b];k c;j(/\\.7p$/.1H(d))j(e){j(!e.3O&&e.17)e=e[0];j(e.3O){e.3O(d,!!f);o}}j(G.2z["4Z/4Y"]||G.2z["4Z/x-4Y"]){c=$("4X");j(!c){c=T.2Y("3N");c.1G("q","4X");T.2a.1W(c)}c.2x="<4W 1X=\\""+d+"\\" 7o=\\"J\\" 3M=\\"B\\" "+"4M=\\"J\\" />"}}}})();k 7n=7m.7l({1V:1t,2W:[],7k:g(){u.2W=[];4V=$$(\'2a\')[0];u.1V=4V.1W(M P(\'1a\',{q:\'7j\'}));u.4U()},4U:g(){2y=u;3J=0;$$(\'.2V\').3L(g(F){j(F.16&&F.16.17>0){F.2X(\'7i\',(g(R){R.3K;2y.4T(R)}).29(u));F.2X(\'7h\',(g(R){R.3K;2y.4R(R)}).29(u));F.2X(\'7g\',(g(R){R.3K;2y.4O(R)}).29(u));2y.2W[3J]=F.16;F.4S=3J++;F.7f(\'16\')}})},4T:g(R){2w=R.3H(\'.2V\');j(2w){u.1V.2x=u.2W[2w.4S];u.1V.z.4N="7e";1s=R.3H(\'.2V 3I\');j(1s)1s.7d=\'\'}},4R:g(R){2w=R.3H(\'.2V\');j(2w){u.1V.z.28=(R.4Q()+10)+\'1F\';u.1V.z.1r=(R.4P()+10)+\'1F\'}},4O:g(R){u.1V.z.4N="4M"}});',62,724,'||||||||||||||||function|||if|var||MindMeister||return||id||||this|tip||||style||false|_|element|win|el|navigator|else|width|true|height|elements|new|tr|onload|Element|name|event|ui|document|icon|window|match|Object|utils|url||null|dialogDefaultOptions|extend|map_id|value|title|length|map|indexOf|div|h2|dlg_name|userAgent|mapPage|location|href|canvas|dialog|typeof|button|elem|file|i18n|callback|call|exception|top|image|undefined|disabled|buttonClass|showCenter|setDestroyOnClose|setAjaxContent|Window|closeAll|ContextMenu|type|dlg_options|param|px|setAttribute|test|console|App|appVersion|cleaned|icons|align|field|windowParameters|OK|okLabel|Dialog|current_language|message|objTip|appendChild|src|txt|readyState|arguments|pos|className|dialogs|text||email|_skypeAvailable|left|bind|body|Firefox|MSIE|isIE6|isIE|res|none|onclick|class|glue|distance|100|img_path|backgroundImage|addClassName|while|ml|asynchronous|Request|Ajax|small|font|target|innerHTML|me|mimeTypes|protocol|getTime|Date|Safari|update|removeClassName|def|config|users|page|ok|confirm|alert|params|path|who|plugins|flashvars|action|alertMsg|details|tooltip|tipsArray|observe|createElement|catch||try|application|iPad|timer|done|addEventListener|complete|loaded|setInterval|push|for|len|setCookie|li|h3|tail|opacity|parentNode|eY|eX|show|useCSS3|420|status|direction|sync_progress|400|contentPath|en|evalScripts|getMap|mapList|maps|_dlgGoOffline|collaborator|close|260|signin|skypeMime|html|callbackTimer|logMessage|findElement|img|cnt|stop|each|loop|span|playSound|hasFlash|end|ie_console|isSF|flash|shockwave|Windows|Opera|isOP|clearInterval|insert|priority|groups|break|substring|newIcons|charAt|split|css|Icon|700|help|getCookie|clientWidth|onclick_old|href_old|offsetHeight|png|cacheBuster|ServerData|offset|hide|selected|Offline|isOffline|up|500|complete_signup|removeFocus|440|root_id|home|tableClass|margin|bold|weight|120|size|hasClassName|controller|login|showAjaxDialog|func|win_name|object|password|onreadystatechange|expires|data|hidden|visibility|handleMouseOut|pointerY|pointerX|handleMouseMove|tip_num|handleMouseOver|setupTooltips|objBody|embed|sound|mpeg3|audio|resources|host|init|Sound|loadSWF|ieTime|profile|mm_profile|9px|zIndex|10px|log|isFF|debugMode|isCH|hasCSS3|isAndroid|isIPad|isIPhone|iPhone|Linux|Macintosh|Chrome|cache|listen|iew32|attachEvent|load|delete|task|join|Number|last|htc|iepngfix|default|skins|stylesheets|behavior|time_tip_seen|seen_tip_|positionTip|_tip|ul|timeTip|setHeight|setWidth|right|offsetLeft|clientHeight|offsetTop|clonePosition|alpha|filter|enableButton|textDecoration|file_label|offsetWidth|Event|forceGIF|effectOptions|480|580|borderColor|btn_toggle|inactive|isExternal|allowAction|down|case|marginLeft|_animateGoogleInt|_animateDialogExportToGoogle|setTimeout|RevisionBrowser|node_title|showDialog|loadScript|loadCSS|closable|doWithdrawFromMap|doDeleteMap|360|180|js_do_you_want_to_continue|doRemoveCollaborator|320|140|who_has|cancelChangeEmail|doChangeEmail|other|ServerConnection|doClone|export_ajax|api_key|feedback_ajax|langmenu|useEffects|dlg_login|_ajax|getLocalURL|download|_skypeCheck|skype|fld|username|Flash|Shockwave|transparent|wmode|com|macromedia|http|allowFullScreen|always|allowScriptAccess|middle|ffffff|bgcolor|best|quality|menu|https||head|getElementsByTagName|names|cookie||re|port|reportException|stackTrace|description|stack|error|alt|visible|removeAttribute|mouseout|mousemove|mouseover|tooltips_tip|initialize|create|Class|ToolTips|autostart|mp3|play|swfex|swf_id|SoundPlayer|sound_player_holder|so_sound_player|ms|profileEnd|scroll|overflowY|lineHeight|fontSize|ffc|backgroundColor|9999|100px|340px|absolute|position|setStyle|Version|isTouchDevice|isLinux|isWin|isMac|isIE7|isFF2|mindmeister|DOMContentLoaded|khtml|WebKit|add|compactAndOrder|ICONS|getGroup|toUpperCase|js_priority_s|js_done_lower|js_complete|js_not_started|js_none|getTitle|default_height|default_width|get|background|dark|setIcon|TIPS|last_tip_seen|js_learn_more|openHelpWindow|more|js_close|remove|content|3600000|force|display|showTip|above|disableButton|onmouseout|underline|cumulativeOffset|onmousemove|cabinet|stylizeFile|setTransImg|setTransBG|repeat|no|backgroundRepeat|backgroundPosition|gif|setClippedBG|formSpace|msg_|setFieldVisible|overflowX|afterFinish|hideEffectOptions|duration|innerHeight|recenterAuto|resizable|maximizable|minimizable|1100|popup|fc6fd2|buttonSelect|000|buttonUnselect|isButtonSelected|buttonDisable|buttonEnable|isButtonEnabled|fieldDisable|fieldEnable|fieldDeactivate|fieldActivate|hasUnsavedExternalChanges|getGlobalStatus|270|switch|0px|30px|hideEffect|showEffect|bar|progresscontainer|js_please_wait_while_we_save_to_google_docs|center|dialog_export_to_google|info|dialogExportToGoogle|revision_browser_js|revision_browser_css|historyDialog|completeSignupProcess|window_trashcan|trashcan_ajax|dialogTrashcan|copy|doCopyMap|withdraw|js_you_are_about_to_withdraw|js_withraw_from_map|dialogWithdrawFromMap|js_you_are_about_to_delete|js_delete_mind_map|dialogDeleteMap|border|280|preview|converttoimage|loading_bg|js_preview_of|dialogPreviewMap|doShowMap|offline_ajax|map_list|dialogGoOffline|150|js_inaccessible_map|js_map_not_share_with_u_anymore_contact_s|dialogMapInaccessible|user_id|do_share_remove_collaborator|js_removing_collaborator|js_s_still_has_tasks_sure_want_to_remove_s|js_you_still_have_tasks_sure_want_to_withdraw|self|dialogRemoveCollaborator|cancel_change_email|user|toQueryString|do_change_email|cancel|js_change_email_requires_reactivation_are_u_sure|js_reactivation_required|dialogConfirmChangeEmail|cloneMap|130|js_do_you_want_to_clone_map_to_account|js_clone_public_map|dialogCloneMap|print_ajax|460|340|print|dialogPrintMap|parameters|external|200|export|dialogExportMap|feedback|dialogFeedback|lang||cm_link|contextmenu|closeContextMenu|sign_in_button_|gapps|gmail|openid|standard|activate|switchLogin|setContent|showLoginDialog|navigationMode|closeAjaxDialog|update_listing|getLocalCssURL|SiteURLSecure|remote|downloadskype|js_user_has_no_skype_account_or_has_not_provided_one|lastClickedUser|doSkypeCheck|namedItem|Mozilla|isSkypeInstalled|loginClear|password_bg_image|username_bg_image|loginCheck|getflashplayer|go|www|pluginspage|movie|version|cab|swflash|cabs|pub|codebase|444553540000|96b8|11cf|ae6d|d27cdb6e|clsid|classid|swf|screen|media|stylesheet|rel|link|javascript|script|getElementById|unescape|exec|gi|RegExp|toUTCString|escape|64e7|setTime|reload|reloadPage|domain||getSiteURL||empty|requestLog|_mm_file_versions|toJSON|shareMode|reportMapException|logException|backtrace|escapeHTML|AppException|throw|ResponseNotMatching'.split('|'),0,{}))
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)d[e(c)]=k[c]||e(c);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('q.p.1D=3(o,a,1g){2.o=$(o);2.1g=1g||"B";2.14=[];2.M=1h 2R();6(a){2.L(a)}2.X=((2Q||(2P&&2O))?2.1z:2.12).2N(2);2.1w()};q.p.1D.1v={x:m,L:3(a){6(1a.19(a)){K(5 i=0,s=a.J;i<s;i++){2.L(a[i])}c}5 4=a.7,l=a.l,h=a.h,g=a.g,f=a.f,d=a.d,u=a.u||a.1c,t=a.t;6(1A 4=="2M"){5 1e=[];5 1f=4.2L();K(5 j=0,s=1f.J;j<s;j++){1e.1B(1f.2K(j))}4=1e}5 1d=1a.19(4);6(1d){2.1b(4.2J(3(k){c{C:k,l:l,h:h,g:g,f:f,d:d}}))}15{2.1b({C:4,l:l,h:h,g:g,f:f,d:d})}5 1c=3(e){6((l&&!e.r)||(h&&e.r)||(g&&!e.n)||(f&&e.n)||(d&&!e.v))c;5 k=e.P();6(1d){K(5 i=0,s=4.J;i<s;i++){6(4[i]==k){6(2.x){e.x()}u.I(t||1C);c}}}15{6(k==4){6(2.x){e.x()}u.I(t||1C,k,e)}}};2.14.1B(1c)},1b:3(7){2.M=2.M.2I(7)},1y:3(e){5 C=e.P();c 2.M.2H(3(k){c(C==k.C)&&(!!k.d==!!e.v)&&(!!k.l==!!e.r)&&(!!k.g==!!e.n)&&(!!k.h?!e.r:m)&&(!!k.f?!e.n:m)})},2G:3(7,u,t){5 4,l,g,d,h,f;6(1A 7=="2F"&&!1a.19(7)){4=7.7;l=7.l;h=7.h;g=7.g;f=7.f;d=7.d}15{4=7}2.L({7:4,l:l,h:7.h,g:g,f:7.f,d:d,u:u,t:t})},12:3(e){e=q.p.W.G(e);6(2.y){5 b=2.14;K(5 i=0,s=b.J;i<s;i++){b[i].I(2,e)}}},1z:3(B){5 11=0;5 H=3(z){6(11==0){2.12.I(2,z)}6(2.1y(q.p.W.G(z)))z.V();11++}.2E(2);H(B);5 10=2;2.o.Z("1x",3(z){10.o.Y("T",H,m);10.o.Y("1x",2D.2C,m);z.V()},m);2.o.Z("T",H,m)},2B:3(){c 2.y},1w:3(){6(!2.y){2.o.Z(\'B\',2.X);2.y=m}},2A:3(){6(2.y){2.o.Y(\'B\',2.X);2.y=A}}};q.p.W=3(){5 O={2z:1t,2y:Q,2x:1s,2w:F,2v:E,2u:1u,2t:1r,2s:R,2r:S,2q:1q,2p:1p};q.p.N=3(e){6(e){2.G(e.w||e)}};q.p.N.1v={w:2o,2n:-1,r:A,n:A,v:A,2m:8,1n:9,1o:13,2l:13,2k:16,2j:17,1m:27,2i:2h,2g:E,2f:1u,2e:S,2d:R,2c:1t,2b:1s,2a:Q,29:F,28:1l,26:1r,25:1q,24:23,22:21,1Z:1p,1Y:1X,1W:1V,1U:[1T,1S,1R,1Q],1P:[1O,1N,1M],G:3(e){6(e==2||(e&&e.w)){c e}2.w=e;2.U=e.U;2.r=e.r;2.n=e.n||e.1L;2.v=e.v;2.4=e.4;2.D=e.D;2.1K=e.1J();c 2},x:3(){6(2.w){2.w.V()}},1I:3(){5 k=2.4;k=1k.1j.1i?(O[k]||k):k;c(k>=E&&k<=F)||k==2.1o||k==2.1n||k==2.1m},1H:3(){5 k=2.4;c(2.U==\'T\'&&2.n)||k==9||k==13||k==F||k==27||(k==16)||(k==17)||(k>=18&&k<=20)||(k>=E&&k<=S)||(k>=R&&k<=Q)||(k>=1G&&k<=1l)},1F:3(){c 2.D||2.4},P:3(){5 k=2.4||2.D;c 1k.1j.1i?(O[k]||k):k},1E:3(){c((2.n||2.v)||2.r)?m:A}};c 1h q.p.N()}();',62,178,'||this|function|keyCode|var|if|key|||config||return|alt||notCtrl|ctrl|notShift||||shift|true|ctrlKey|el|utils|MindMeister|shiftKey|len|scope|fn|altKey|browserEvent|stopEvent|enabled|event|false|keydown|code|charCode|33|40|setEvent|handleEvent|call|length|for|addBinding|_usefulKeys|KeyEventImpl|safariKeys|getKey|39|36|35|keypress|type|stop|KeyEvent|_listener|stopObserving|observe|_this|firedCount|handleKeyDown||bindings|else||||isArray|Object|addUsefulKeys|handler|keyArray|ks|keyString|eventName|new|WebKit|Browser|Prototype|45|ESC|TAB|RETURN|119|113|46|38|37|34|prototype|enable|keyup|isUsefulEvent|handleKeyDownHacked|typeof|push|window|KeyMap|hasModifier|getCharCode|44|isSpecialKey|isNavKeyPress|element|target|metaKey|189|109|95|MINUS|187|107|61|43|PLUS|121|F10|120|F9|F8||117|F6|116|F5|F2|DELETE||INSERT|DOWN|RIGHT|UP|LEFT|HOME|END|PAGEDOWN|PAGEUP|32|SPACE|CONTROL|SHIFT|ENTER|BACKSPACE|button|null|63243|63237|63275|63273|63272|63277|63276|63233|63232|63235|63234|disable|isEnabled|callee|arguments|bind|object|on|any|concat|collect|charCodeAt|toUpperCase|string|bindAsEventListener|isMac|isFF2|isOP|Array'.split('|'),0,{}))
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)d[e(c)]=k[c]||e(c);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('1G.1F(C.U,{4q:7(B,11){a L=8(\'4p\');4(!L)e;L.I="<p>"+B+"</p>";4(4o)w.2p(L);9 f 2I.4n(L,{11:0.5});J(7(){f 2I.4m(L,{11:0.5})},11?(11*27):4l)},2C:7(B,1N){f H.O("/4k/4j?B="+1y(B)+"&1N="+1y(1N),{1o:n,17:n})},4i:7(1k,1j,o,2H,10,l){a 2G=10?(\'&\'+l):(\'?\'+l);4(2H)f H.4h(1j+\'4g\',\'/18/\'+1j+\'8\'+1k+\'4f/\'+o+(10?"?10="+10:"")+(l?2G:\'\'),{17:n});9{8(\'1k\').6=1k;8(1j+\'4e\').4d()}e A},4c:7(1h,1g,1i){a 1M=\'/1L\';4(1i&&1i!="4b"){a 1M=\'/\'+1i+\'/1L/4a\'}m.49(1M+(1h?"?1h="+1h:"")+(1g?"&1g="+1g:""),\'1L\',\'1x=48,1w=47,46=2E,45=2E,44=2D,43=2D\');e A},42:7(o,E,1K){f H.O(\'/18/41/\'+o+\'?E=\'+E+\'&1K=\'+1K,{1o:n,17:n});e A},40:{r:g,d:g,c:g,t:g,Z:g,2B:7(r,d,t,Z){2.r=r;2.d=d;2.Z=Z;2.c=f 2A.2z(2.r,2.d,{2y:\'2x\',2w:$R(1,b),2v:2.j.D(2),2u:2.1c.D(2)});2.t=t;4(2.t){2.c.h(b);2.j(b)}9{2.c.h(1);2.j(1)}},j:7(6){4(6>V){w.1f(2.d,{1e:"0 b%"})}9{w.1f(2.d,{1e:"0 0"})}},1c:7(6){4(!2.Z&&6<=V){C.U.2C(\'3Z\'.P(),\'3Y\'.P());2.c.h(b);2.j(b);e}2.j(6);4(6==b){4(!2.t){2.t=n;J("3X.3W()",b)}}9 4(6==1){4(2.t){2.t=A;J("C.1B.3V.3U()",b)}}9 4(6>V){2.c.h(b)}9{2.c.h(1)}},3T:7(){2.t=A;2.c.h(1)},3S:7(){2.c.h(b)}},3R:3Q.3P({r:g,d:g,c:g,y:g,Y:g,X:g,K:g,2B:7(r,d,y,Y,X,K){2.r=r;2.d=d;2.Y=Y;2.X=X;2.K=K;2.c=f 2A.2z(2.r,2.d,{2y:\'2x\',2w:$R(1,b),2v:2.j.D(2),2u:2.1c.D(2)});2.y=y;4(2.y){2.c.h(b);2.j(b)}9{2.c.h(1);2.j(1)}},j:7(6){4(6>V){w.1f(2.d,{1e:"0 b%"})}9{w.1f(2.d,{1e:"0 0"})}},W:7(){3O.3N(\'<1d o="3M" 2s="2r"><2t>\'+\'3L\'.P()+\'</2t></1d><3K /><1d 2s="2r"><2q>\'+\'3J\'.P()+\'</2q></1d>\',{3I:\'3H\',3G:"3F",3E:1G.1F(C.U.3D(),{1x:3C,1w:3B,3z:{},3y:w.2p,3x:w.3w})})},1J:7(E){4(2.K){m.N=2.K}9{4(E=="2m"){f H.O(2.Y,{2o:2.W.D(2),2n:2.W.D(2)})}9 4(E=="2l"){f H.O(2.X,{2o:2.W.D(2),2n:2.W.D(2)})}}},1c:7(6){2.j(6);4(6==b){4(!2.y){2.y=n;2.1J("2m")}}9 4(6==1){4(2.y){2.y=A;2.1J("2l")}}9 4(6>V){2.c.h(b)}9{2.c.h(1)}}}),1H:{3v:7(){2.1I(3u);J("C.U.1H.1I(3t)",3s)},1I:7(2k){w.3r("2i","2h");J("C.U.1H.2j()",2k)},2j:7(){w.3q("2i","2h")}}});1G.1F(C.1B,{3p:7(v){a v=(v||8(\'3o\').6).3n();4(8(\'1E\').2g||8(\'1D\').2g||v.1C("@")<0)e;a T=v.3m(/^([^@\\s]+)([\\.\\8\\-]+)([^@\\s]+)@(.*)$/);4(T&&T[0]){8(\'1E\').6=T[1].1b();8(\'1D\').6=T[3].1b()}9 4(v.1C("@")>0){8(\'1E\').6=v.3l(0).1b();8(\'1D\').6=v.3k(1,v.1C("@")).1b()}},3j:7(F,Q,1a,1z){4(!1z){C.1B.2e(F,Q,1a);e}4(!F)e;4(m.19){m.19.2d(\'2c\',F.6);e}1A.3i(m.N.1n+"//"+m.N.3h+\'/2b/1A.2a\');a S=f 1A.3g();S.3f(n);S.3e(F.6);S.3d(\'3c\',1z);a 2f=S.3b(1a[0],1a[1]);Q.I=2f},2e:7(F,Q,28){a u=8(F),1v;4(!u||u.3a)e;4(m.19)m.19.2d(\'2c\',u.6);9{8(Q).I=\'<29 G="/2b/39.2a" 38="37=\'+1y(u.6)+\'" 1x="0" 1w="0" E="36/x-35-34"></29>\'}u=8(28);1v=u.I;u.I=\'<p 33="32">\'+\'31\'.P()+\'</p>\';J(7(){u.I=1v},27)},30:7(1s,1t,26){a 1u=[];2Z(a i=0;i<1s.2Y;i++){1u[i]=f 2X();1u[i].G=(1t?1t:"")+1s[i]+(26?"":"?"+2W.2V)}},2U:7(o,z,l){a k=12.21("20");4(m.N.1n=="16:"){k.G=\'/18/2T?24=\'+o+\'&23=15&22=\'+z+\'&\'+14.13(l)}9{k.G=\'2S://25.1m.1l/25/2R.2Q?24=\'+o+\'&23=15&22=\'+z+\'&\'+14.13(l)}12.1P.1O(k);e k},2P:7(o){a B=8(\'2O\').6||\'\';a 1r=8(\'2N\').6||\'\';a 1q=8(\'2M\').6||\'\';a 1p=8(\'2L\').6||\'\';f H.O(\'/18/2K/\'+o+\'?B=\'+B+\'&1r=\'+1r+\'&1q=\'+1q+\'&1p=\'+1p,{1o:n,17:n})},2J:7(M,z,l){a k=12.21("20");4(m.N.1n=="16:"){k.G=\'16://M.1m.1l/1Z/1Y/1X?q=\'+M+\'&1W=A&1V=1U%3A%2F%1T.1S%1R&1Q=15&z=\'+z+\'&\'+14.13(l)}9{k.G=\'16://M.1m.1l/1Z/1Y/1X?q=\'+M+\'&1W=A&1V=1U%3A%2F%1T.1S%1R&1Q=15&z=\'+z+\'&\'+14.13(l)}12.1P.1O(k);e k;}});',62,275,'||this||if||value|function|_|else|var|100|control|container|return|new|null|setValue||handleSlide|data|params|window|true|id|||element||online|elem|email|Element||onoff|callback|false|message|MindMeister|bind|type|text_el|src|Ajax|innerHTML|setTimeout|urlRedirect|msgbar|query|location|Request|tr|copy_el||clip|out|ui|50|failedOnOff|urlOff|urlOn|feature_gears|edit|duration|document|toQueryString|Hash|json|https|evalScripts|dialog|clipboardData|dimensions|capitalize|handleChange|div|backgroundPosition|setStyle|subtopic|topic|locale|dlg|tab|com|yahooapis|protocol|asynchronous|subject|from|invitees|images|prefix|imgArr|tmp|height|width|encodeURIComponent|complete_func|ZeroClipboard|utils|indexOf|user_lastname|user_firstname|extend|Object|promotion|showBlitz|executeAction|map_id|help|path|hint|appendChild|body|format|2Falltableswithkeys|org|2Fdatatables|store|env|diagnostics|yql|public|v1|script|createElement|_callback|_render|_id|pipes|no_cb|1000|message_el|embed|swf|resources|Text|setData|copyToClipboard2|html|touched|blitz|promotion_blitz|hideBlitz|dur|off|on|onException|onFailure|show|h3|center|align|h2|onChange|onSlide|range|horizontal|axis|Slider|Control|initialize|showUpgradeMessage|yes|no||param|switchOnly|Effect|loadYQL|share_public|forward_subject|forward_from|forward_to|forward_message|sendPublicMapShare|run|pipe|http|auto_link_proxy|loadPipe|cacheBuster|ServerData|Image|length|for|preloadImages|js_copied_to_clipboard|emph|class|flash|shockwave|application|cliptext|FlashVars|clipcopy|disabled|getHTML|complete|addEventListener|setText|setHandCursor|Client|host|setMoviePath|copyToClipboard|substring|charAt|match|toLowerCase|user_email|prefillUserName|removeClassName|addClassName|160|220|80|blitzTwice|hide|hideEffect|showEffect|effectOptions||200|400|dialogDefaultOptions|windowParameters|OK|okLabel|button|buttonClass|js_we_are_sorry_request_was_not_done|br|js_your_request_was_not_done|dialog_onofffailed|alert|Dialog|create|Class|OnOffSlider|forceOnline|forceOffline|dialogGoOffline|dialogs|goOnline|Offline|js_use_offline_mode|js_go_offline|slider|nudge_friend|nudgeFriend|resizable|scrollbars|toolbar|menubar|700|1020|open|index|en|openHelpWindow|onsubmit|_form|_ajax|_content|Updater|switchTab|msg_upgrade|users|4000|BlindUp|BlindDown|isSF|messagebar|showFlashMessage'.split('|'),0,{}))
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)d[e(c)]=k[c]||e(c);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('b M=1D.1C({f:14,v:14,1B:6(13){3.f=$(13)},K:6(12){3.f.K();5(!12){3.v=3.11.I(3);10.m(\'Z\',3.v)}},d:6(){5(3.f)3.f.d()},11:6(Y){10.1A(\'Z\',3.v);b L=p.l(Y);5(!L.7.X("J")&&!L.1z.7.X("J")){3.d()}},1y:6(n,g){3.f.1x=\'\';3.f.N(3.H(n));3.f.1w(\'a.j\').W(\'m\',\'1v\',6(e){5(3.7.C(\'q\')!=-1)F;3.V(\'.G\').S({1u:\'-1t\',1s:(3.1r()-(1q?8:2))+\'1p\'}).K(1o)}).W(\'m\',\'1n\',6(e){5(e.l().7.C(\'j\')==-1)F;3.V(\'.G\').d()});5(g)g.1m()},H:6(n){b E=t s(\'1l\');1k(b i=0,U=n.1j;i<U;i++){b 4=n[i];5(4){b l=t s(4.u?\'1i\':\'1h\',{7:4.u?\'u\':\'\'});5(!4.u){b 9=t s(\'a\',{1g:\'#\',1f:4.T,7:\'J \'+(4.7||\'\')+(4.q?\' q\':\'\')+(4.j?\' j\':\'\')});Q.P(9,{h:4.g});9.m(\'O\',4.g?3.D.I(3):p.A);9.1e(4.T);5(4.c){b c=t s(4.c.1d||\'1c\');5(4.c.R)c.S(4.c.R);Q.P(c,{h:4.g});c.m(\'O\',4.g?3.D.I(3):p.A);9.N(c)}5(4.j){9.r(3.H(4.j).1b({7:\'G\',1a:\'19:18\'}))}B{9.r(\'\')}}B{b 9=\'\'}l.r(9);E.r(l)}}F E},D:6(e){5(e.k.h&&e.k.7.C(\'q\')==-1){3.d();5(o e.k.h=="17")16(e.k.h);B e.k.h()}p.A(e)}});M.15=6(){5(o z!=\'x\'&&z)z.d();5(o y!=\'x\'&&y)y.d();5(o w!=\'x\'&&w)w.d()};',62,102,'|||this|item|if|function|className||new_el||var|subitem|hide||el|callback|_callback||submenu|target|element|observe|items|typeof|Event|disabled|insert|Element|new|separator|hideListener|connectionMenu|undefined|nodeMenu|mapMenu|stop|else|indexOf|onSelect|list|return|contextmenu|createList|bindAsEventListener|cm_link|show|clicked|ContextMenu|appendChild|click|extend|Object|_style|setStyle|name|len|down|invoke|include|event|mousedown|document|hideOnClick|permanent|id|null|closeAll|eval|string|none|display|style|wrap|span|tag|update|title|href|li|div|length|for|ul|call|mouseout|true|px|isIE6|getWidth|left|1px|top|mouseover|select|innerHTML|addItems|parentNode|stopObserving|initialize|create|Class'.split('|'),0,{}))
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)d[e(c)]=k[c]||e(c);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('p n=3v.3u({t:i,K:i,l:i,8:i,1O:i,o:i,D:i,1n:i,O:i,1B:7(K,l,3A){6.t=n.t++;6.K=K;6.l=l;6.1n=3A;6.O=0;6.1O=""},1b:7(8){1N{6.8=8;1N{p z=8.B()}1M(e){f.h.1c(i,\'3z 8 (\'+e.M+\')\',i,f.S.13)}9(1x z!="60")f.h.1c(i,"3z 8",i,f.S.13);9(z.o)F.o=z.o;9(z.D&&g.q.1w)F.D=z.D;9(1x g.q.3y!=\'1I\')g.q.3y(z);9(6.1n){6.1n(z)}9(z.h){1v(z.h.V){R 1:f.h.1c(i,z.h.M,i,f.S.13)}}}1M(1R){9(g.q.2f&&(g.q.2f==\'5Z\'||g.q.2f==\'5Y\'))(1D&&1D.h)?1D.h(1R):5X(1R);w f.h.1c(1R,i,i,f.S.13)}},3e:7(){[\'/c/23\',\'/c/2V\',\'/c/2u\',\'/c/2D\'].5W(7(u){b u==6.K})},3r:7(){b"5V: "+6.t+"\\5U: "+6.K+"\\5T: "+6.o+"\\5S: "+6.D+"\\5R: "+U.I(6.l)+"\\5Q: "+6.8},1y:7(){6.O+=1;b(6.O>4)?i:6},3t:7(){b j 3w(6)},3h:7(3x){6.1O=3x}});n.t=0;p 3w=3v.3u(n,{15:i,O:i,1B:7($1Q,15){6.15=15;6.D=15.D;$1Q(\'/c/5P\',i,i)},1b:7($1Q,8){p z=8.B();9(z.2a!=6.15.t)5O j f.h.3g();6.1n=6.15.1n;$1Q(8)},1y:7(){b 6.15.1y()},3t:7(){6.O+=1;b(6.O>4)?i:6}});p A={1s:1,N:j 5N(),1z:i,24:0,1L:i,1B:7(){6.31=6.3i.1a(6);6.29=6.3f.1a(6);6.30=6.39.1a(6)},5M:7(2e){9(6.26()){6.1z=2e}w{2e()}},35:7(m){g.3n(v);9(g.3s)g.3s.2X(m);9(m.K==\'/c/2K\')6.24=j 1l();m.D=j 1l().3r()},G:7(){p 2d=A.N.5L(),1m=2S.2R((j 1l()-j 1l(2d.D)));9(1m>5K&&1x 3q!="1I"){3q.5J("/3j");6.3m(2d,1m);g.q.1P=g.q.1P?g.q.1P+1:1;9(g.q.1P>3&&!g.q.3o&&!f.S.5I("5H")){s.Q("5G\'5F 5E 5D 5C 5B 5A f 5z. 5y 5x 5w 5v 5u 5t 5s <a 5r=\'5q:#5p;5o-5n:5m\' 5l=\'#\' 5k=\'b f.S.5j.5i(\\"3p\\", F.T.t);\'>5h 5g 3p</a>.",s.1S,10);g.q.3o=v}}g.3n(18);A.28()},3m:7(m,1m){1N{p 2b="";9(3l){["3k","5f","5e","5d","5c","5b","5a","59"].37(7(2c){9(3l[2c].58){2b+=2c+";"}})}p 1A=m.1O.57(/X-56: \\d+.\\d+/);1A=(1A?55(1A[0].54(11))*2Q:-1);p l={2a:m.t,O:m.O,8:m.8,o:m.o,53:m.D,K:m.K,52:U.I(m.l),1p:6.1L[\'T\'],1m:1m,51:3k.50,4Z:F.T.4Y(),4X:2b,4W:2n((j 1l()-g.q.4V)),4U:1A,4T:g.q.4S};j W.n(\'/2m/3j\',{34:{\'33\':\'32/z\'},P:l})}1M(e){}},3i:7(k){p r=6.N.1K();1N{9(k.1q==0){r.1b("{h: {V: 10, M: "+\'38\'.1f()+"}}")}w{r.1b(k.E);r.3h(k.4R())}}1M(e){9(e 4Q f.h.3g){p 1d=r.1y();9(1d)6.1J(1d,v);w{6.G();b f.h.1c(i,"n O 3d 3c 3b.",\'3a\'.1f(),f.S.13)}}w{6.G();b f.h.1c(e,i,i,f.S.13)}}6.G();9(A.1z){A.1z();A.1z=i}},3f:7(){p r=6.N.1K(),1d;9(!r.3e()){1d=r.1y();9(1d){6.1J(1d,v);6.G()}w{6.G();f.h.1c(i,"n O 3d 3c 3b.",\'3a\'.1f(),f.S.13)}}w 6.G()},39:7(k){p r=6.N.1K();r.1b("{h: {V: 10, M: "+\'38\'.1f()+"}}");6.G()},2Z:7(m){9(!g.q.4P&&!g.q.1w)b 6.G();9(f.1q.4O()){9(!m.l||!m.l.1W)b 6.G();F.o++;p 1F={1p:F.T.t,4N:19.4M[0],o:F.o};2i.4L(1F,m.l);b 6.G()}w 9(f.1q.4K){9(!m.l||!m.l.1W)b 6.G();9(m.l.1r>0)f.1q.2k=v;m.l.37(7(36){g.4J.2N(36)});b 6.G()}w{m.o=F.o;6.35(m);6.1L={T:F.T.t,2q:F.o,2a:m.t,1s:A.1s,l:U.I(m.l)};j W.n(m.K,{34:{\'33\':\'32/z\'},P:6.1L,1e:A.31,1T:A.29,2r:A.29,4I:A.30})}},28:7(){9(6.N.2W())b;2I(7(){6.2Z(6.N.1K())}.1a(6),10)},1J:7(m,2Y){2Y?6.N.4H(m):6.N.2X(m)},y:7(m){6.1J(m);9(6.N.4G()==1)6.28()},26:7(){b 6.N.2W()?18:v},4F:7(27,1g){p l={27:27,1g:1g};6.y(j n(\'/c/2V\',l,i))},4E:7(){6.y(j n(\'/c/4D\',i,6.2U))},2U:7(8){(8.2A)?f.C.1k(\'1j\'):f.C.12(\'1j\');(8.2x)?f.C.1k(\'Z\'):f.C.12(\'Z\')},23:7(){9(A.26())b;p 14={};1v(g.q.21){R g.4C:9(!g.q.1w)b;9(1x 1H!=\'1I\'&&1H.2T)b;14.25=v;14.1w=v;14.D=F.D;1i;R g.4B:1i;R g.20:9(1x 1H!=\'1I\'&&1H.2T)b;14.25=v;1i;4A:b}9(14.25){9(2S.2R((j 1l()-6.24)/2Q)<5)b}9(!g.q.1w)14.4z=f.2O.D;6.y(j n(\'/c/23\',14,6.2P))},2P:7(8){9(8.h&&8.h.V==2){g.4y()}w 9(8.h&&8.h.V==4){f.S.13()}w{9(8.19)19.4x(8.19);9(8.x&&8.x.1r>0){1h.1u(8.x)}9(8.22)f.2O.4w(8.22.4v,8.22.D)}},4u:7(2M){p L=j 1h();L.x.2N(2M);6.2L(L)},2L:7(L){9(L.x.1r==0)b;6.y(j n(\'/c/2K\',L.I(),L.1b.1a(L)))},2H:7(1G,t){6.y(j n(\'/c/2j/\'+t,i,6.2J.1a(6,1G)))},2J:7(1G,8){9(8.x){2I(7(){F.T.4t(1G).2H(8.x)},10)}},4s:7(1t,L,2G,2F){p 2E="?4r="+!!2G+"&4q="+!!2F;6.y(j n(\'/c/4p/\'+1t+2E,L.I(),L.1b.1a(L)))},4o:7(l){6.y(j n(\'/c/2D\',l,i))},2C:7(){6.y(j n(\'/c/2C\',{},6.2B))},2B:7(8){9(8.h){1v(8.h.V){R 3:g.1Z();1i;R 10:s.Q(8.h.M,s.17);f.C.12(\'1j\')}}w 9(g.q.21!=g.20){(8.2A)?f.C.1k(\'1j\'):f.C.12(\'1j\');f.C.1k(\'Z\');1h.1u(8.x)}},2z:7(){6.y(j n(\'/c/2z\',{},6.2y))},2y:7(8){9(8.h){1v(8.h.V){R 3:g.1Z();1i;R 10:s.Q(8.h.M,s.17);f.C.12(\'Z\')}}w 9(g.q.21!=g.20){(8.2x)?f.C.1k(\'Z\'):f.C.12(\'Z\');f.C.1k(\'1j\');1h.1u(8.x)}},1Y:7(o){6.y(j n(\'/c/1Y\',{o:o},6.2w))},2w:7(8){9(8.h){1v(8.h.V){R 3:g.1Z();1i;R 10:s.Q(8.h.M,s.17)}}w{f.C.12(\'Z\');1h.1u(8.x)}},4n:7(o){6.y(j n(\'/c/1Y\',{o:o},6.2v))},2v:7(8){9(8.h){9(8.h.V==10)s.Q(8.h.M,s.17)}w{f.C.12(\'Z\');1h.1u(8.x)}},4m:7(o){6.y(j n(\'/c/4l\',{o:o}))},4k:7(2t,16){6.y(j n(\'/c/2u\',2t,16))},4j:7(1t,1g){6.y(j n(\'/c/4i\',{1t:1t,1g:1g},6.2s))},2s:7(8){9(8.h){s.Q(8.h.M,s.17)}},4h:{J:7(K,P,1E){p 8,l={1s:A.1s};P=$H(l).4g(P);j W.n(K,{1o:18,P:P,1e:7(k){8=(1E)?1E(k):v},1T:7(){8=18},2r:7(){8=18}});b 8},4f:7(1X,1F){9(1X.1r==0)b v;p 2p=1X.1W(7(1V){b{2q:1V.o,x:1V.I()}}),l={T:1F.1p,l:U.I(2p)};b 6.J(\'/c/4e\',l,7(k){p Y=k.E.B();b(Y.h)?18:v})},4d:7(){b 6.J(\'/c/4c\')},4b:7(){6.J(\'/19/4a\')},49:7(t){b 6.J(\'/c/48/\'+t,i,7(k){b k.E.B().o})},47:7(c){b 6.J(\'/c/46\',{c:U.I(c)},7(k){b k.E.B()})},45:7(c){b 6.J(\'/c/44\',{c:U.I(c)},7(k){b k.E.B()})},43:7(){b 6.J(\'c/2o\',{},7(k){b k.E.B()})},42:7(c){b 6.J(\'c/2o\',{c:U.I(c)},7(k){b k.E.B()})},41:7(){b 6.J(\'/19/40\',3Z.3Y(\'3X\',v),7(k){b k.E.B().1E})},3W:7(1U){b 6.J(\'/c/3V\',{1U:1U},7(k){b 2n(k.E.B().t)})},3U:7(){b 6.J(\'/19/3T\',i,7(k){b k.E.B()})}},3S:{3R:7(l){9(g.3Q){1D.2m(l)}w{j W.n(\'/c/3P\',{1o:v,P:{l:U.I(l)}})}},3O:7(x,16){9(x.1r==0)b v;j W.n(\'/3N/3M\',{1o:v,P:{l:U.I(x),3L:g.q.3K,2l:g.q.2l},1e:7(){s.Q(\'3J\'.1f(),s.1S);f.1q.2k=18},1T:7(){s.Q(\'3I\'.1f(),s.17)},3H:7(){9(16)16()}})},3G:7(1C,2h){F={o:0,T:1C};j W.n(\'/c/2j/\'+1C.t,{1o:v,1e:7(k){p Y=k.E.B();2i.3F(1C,2h,Y)}})},3E:7(1p){j W.n(\'/c/3D/\'+1p,{1o:v,1e:7(k){p Y=k.E.B();9(Y.h){s.Q(Y.h.M,s.17)}w{s.Q(Y.M,s.1S)}}})},3C:7(t,2g,16){j W.n(\'/c/3B/\'+t,{P:2g,1e:7(k){16(k.E.B().x)}})}}};A.1B();',62,373,'||||||this|function|response|if||return|maps|||MindMeister|App|error|null|new|transport|data|request|Request|revision|var|config||Message|id||true|else|changes|queueAndProcess|json|ServerConnection|evalJSON|ui|timestamp|responseText|tree|_postRequest||toJSON|_do|url|changeList|message|requestQueue|repeated|parameters|show|case|utils|root|Object|code|Ajax||resp|btn_redo|||buttonDisable|reloadPage|param|oldRequest|callback|ERROR|false|users|bind|handleResponse|reportMapException|toRepeat|onSuccess|tr|value|ChangeList|break|btn_undo|buttonEnable|Date|delay|_customHandler|asynchronous|map_id|status|length|protocolVersion|idea_id|executeServerChanges|switch|liveUpdate|typeof|repeat|pendingCode|x_runtime_var|initialize|map|console|success|mapInfo|destination_id|RevisionBrowser|undefined|queueRequest|first|req_data|catch|try|responseHeaders|longRequestCount|super|ex|INFO|onFailure|title|cL|collect|changeLists|revert|toMultiMode|MULTI|shareMode|chat|poll|lastRequestTime|with_data|isPending|key|processQueue|_onRequestFailure|request_id|sideBarVisElements|widget|req|aFunction|environment|params|mapLength|Offline|get_tree|hasUnsavedExternalChanges|api_key|log|parseInt|get_themes|cLData|rev|onException|handleTaskNotify|what|auto_note|handleHistoryRevert|handleRevert|hasRedo|handleRedo|redo|hasUndo|handleUndo|undo|toggle_closed|options|preservePermissions|insertLink|copyExternal|setTimeout|handleCopyExternal|do|doChanges|change|push|Chat|handlePoll|1000|round|Math|on|handleSyncUndoRedo|save_map_preferences|empty|add|highPriority|initRequest|_onRequestTimeout|_onRequestSuccess|application|Accept|requestHeaders|_preRequest|el|each|js_network_problem|requestTimeout|js_connection_to_server_lost|times|many|too|ignorable|requestFailure|ResponseNotMatching|setResponseHeaders|requestSuccess|long_client_request|navigator|sideBar|_sendAjaxDebugInfo|busy|diagnosticsWarningSeen|diagnostics|pageTracker|toString|requestLog|repeatResponse|create|Class|RepeatResponseRequest|respHeaders|handleServerResponseRemote|Bad|handler|get_map_diff|getMapDiff|clone_map|cloneMap|startGetMapWorker|getOriginalMap|onComplete|js_external_save_failed|js_external_save_successful|external_file_id|file_id|save|external|saveExternalChanges|log_client_exception|debugMode|logException|other|get_details|getUserDetails|new_from_offline_map|createNewMap|confirm_password|serialize|Form|client_login|confirmPassword|getMapThemes|getThemplateThemes|get_icons|getIcons|get_maps|getMaps|get_map_revision|getMapRevision|set_gears|toggleUseGears|alive|isOnline|do_all|doAllChanges|merge|sync|set_task_notify|setTaskNotify|autoNote|clone_revision|cloneRevision|historyRevert|toggleClosed|paste_as_map|preserve_permissions|insert_link|pasteAsMap|getChild|doChange|messages|receive|update|toSingleMode|chat_timestamp|default|SHARED|SINGLE|undo_redo_status|syncUndoRedo|setPreference|size|unshift|onTimeOut|externalChanges|isExternal|saveChanges|selfUser|user_id|isOffline|persistent|instanceof|getAllResponseHeaders|premiumUser|is_premium|x_runtime|openedAt|time_opened|side_bar_vis|numChildrenTotal|nodes|userAgent|browser|request_data|date|substr|parseFloat|Runtime|match|visible|info|tasks|attachments|links|notes|icons|format|some|submitting|showAjaxDialog|dialogs|onclick|href|underline|decoration|text|99CCFF|color|style|by|these|solve|us|help|can|You|server|the|with|issues|connection|detected|ve|We|diagnostics_submitted|getCookie|_trackPageview|5000|shift|registerPendingCode|Buffer|throw|repeat_previous_response|nResponse|nData|nTimestamp|nRevision|nUrl|Id|any|alert|preview|development|object'.split('|'),0,{}))
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)d[e(c)]=k[c]||e(c);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('3 6H=2U.2k(4o,{2T:6($2N){$2N(\'6G\')},1S:6($2N,4,Q,3A){7(6F.o(Q).6E.1U()==="6D")b;z.c.3K=z.53(4);3 3B=6C.6B($(\'Z\'+4));z.c.B.4L=(3B[0]+3A[0])+"1x";z.c.B.2D=(3B[1]+3A[1])+"1x";$2N(l);b l},53:6(52){3 s=\'<4T>\';3 h=2.1K(52);7(h.2V){s+=\'<w><1A>\'+\'51\'.j()+\'</1A></w>\';s+=\'<w><1A>\'+\'50\'.j()+\'</1A></w>\';7(h.2l)s+=\'<w><a Y="#" D="1f" T="E.t.V.4Z(\'+h.4+\'); b l;">\'+\'2M\'.j()+\'</a></w>\';y s+=\'<w><a Y="#" D="1f" T="E.t.V.4Y(\'+h.4+\'); b l">\'+\'2M\'.j()+\'</a></w>\';s+=\'<X D="3z"></X>\';s+=\'<w><1A>\'+\'4V\'.j()+\'...</1A></w>\';s+=\'<w><a Y="#" D="1f" T="E.t.V.4X(\'+h.4+\'); b l">\'+\'4W\'.j()+\'</a></w>\';s+=\'<w><1A>\'+\'4U\'.j()+\'</1A></w>\'}y{s+=\'<w><a Y="#" D="1f" T="E.t.V.6A(\'+h.4+\'); b l">\'+\'51\'.j()+\'</a></w>\';s+=\'<w><a Y="#" D="1f" T="E.t.V.40(\'+h.4+\'); b l">\'+\'50\'.j()+\'</a></w>\';7(h.2l){s+=\'<w><a Y="#" D="1f" T="E.t.V.4Z(\'+h.4+\'); b l;">\'+\'2M\'.j()+\'</a></w>\'}y{s+=\'<w><a Y="#" D="1f" T="E.t.V.4Y(\'+h.4+\'); b l;">\'+\'2M\'.j()+\'</a></w>\'}s+=\'<X D="3z"></X>\';s+=\'<w><a Y="#" D="1f" T="E.t.V.4X(\'+h.4+\'); b l">\'+\'4W\'.j()+\'</a></w>\';s+=\'<w><a Y="#" D="1f" T="b E.t.V.2t(\\\'4t\\\', \'+h.4+\',L);">\'+\'4V\'.j()+\'...</a></w>\';s+=\'<w><a Y="#" D="1f" T="E.t.V.6z(\'+h.4+\'); b l">\'+\'4U\'.j()+\'</a></w>\'}s+=\'<X D="3z"></X>\';s+=\'<w><a Y="#" D="1f" T="b E.t.V.2t(\\\'4l\\\',\'+h.4+\',L);">\'+\'6y\'.j()+\'</a></w>\';s+=\'</4T>\';b s}});3 2={v:n,N:n,k:[],3R:6(){2.r=[];2.u=[];14.1H(\'4j\',2.t.4k.O(n));14.1H(\'6x\',2.t.4f.O(n));14.1H(\'6w\',2.t.4e.O(n));14.1H(\'6v\',2.t.2v.O(n,\'4w\'));14.1H(\'6u\',2.t.2v.O(n,\'3b\'));14.1H(\'6t\',2.t.4s.O(n));14.1H(\'6s\',2.t.2v.O(n,\'4v\'))},1c:6(4,g,Q){3 3x=(Q?(6r?Q.6q:Q.6p):l);7(!2.N)2.1J();7(Q&&!(3x||Q.4S)||!Q)2.4B();7(Q&&Q.4S&&(2.v.g==g&&2.v.4!=4||2.v.g!=g)){3 4R=8(2.v.g+\'8\'+2.v.4);3 4Q=8(g+\'8\'+4);3 3y=2.4A(4R,4Q);1v(3 i=0,16=3y.e;i<16;i++){2.2L(3y[i])}}7(Q&&3x&&8(g+\'8\'+4).q.G(\'k\')>=0){2.4P(g==\'h\'?2.1K(4):2.1a(4))}y{7(g==\'h\'){3 3w=2.1K(4);2.v=3w;2.2L(3w)}y{3 3v=2.1a(4);2.v=3v;2.2L(3v)}}7(2.k.e>1){3 1z=2.t.3c;1v(3 i=0,16=1z.e;i<16;i++){3 1q=8(1z[i]+\'3u\');7(1q.1p.q.G(\'2d\')<0)1q.1p.q+=\' 2d\'}}y{3 1z=2.t.3c;7(2.v.g==\'9\'){3 2e=1z.2e(6(1L){b 2.t.3a.4u(1L)});1z=2e[1];1v(3 i=0,16=2e[0].e;i<16;i++){3 1q=8(2e[0][i]+\'3u\');7(1q.1p.q.G(\'2d\')<0)1q.1p.q+=\' 2d\'}}1v(3 i=0,16=1z.e;i<16;i++){3 1q=8(1z[i]+\'3u\');1q.1p.q=1q.1p.q.S(\' 2d\',\'\')}}},2L:6(A){2.k.1n(A);7(8(A.g+\'8\'+A.4).q.G(\'k\')<0)8(A.g+\'8\'+A.4).q+=\' k\'},4P:6(A){2.k=2.k.1c(6(c){b(c.g==A.g&&c.4!=A.4||c.g!=A.g)});7(2.v==A){7(2.k.e>0)2.v=2.k[2.k.e-1];y 2.v=n}8(A.g+\'8\'+A.4).q=8(A.g+\'8\'+A.4).q.S(\' k\',\'\')},6o:6(F){3 9=2.1a(F);9.1B=!9.1B;3 1i=8(\'U\'+F);3 2K=8(\'11\'+F);7(2K){7(9.1B){2K.B.1s=\'\';1i.q+=\' 2s\'}y{2K.B.1s=\'1P\';1i.q=1i.q.S(\' 2s\',\'\')}}2.1k();2.1R();1V(6(){1W.6n(F,9.1B)},2S)},6m:6(){2.2B(8(\'1l\'));2I.4J(\'4O\',{4I:\'\',4G:6(){2.2G("3s")}})},2B:6(R){3 1G=27(R);3 i=1G.e;C(i--){3 o=1G[i];7(o.4&&(o.4.G(\'U\')>=0||o.4.G(\'Z\')>=0)){2.2A(o)}y 7(o.4&&(o.4.G(\'1G\')>=0||o.4.G(\'4O\')>=0)){3 4N=27(o)[0];2.2B(4N)}}},2A:6(o){1N 6l(o.4,{6k:\'6j\',6i:L,4H:\'1l\',6h:6(1e,Q){2.Q=Q;3 p=1e.o.4.3e("8");7(1e.o.q.G(\'k\')<=0)2.1c(p[1],p[0],n);3 4M=2.k.e;7(4M>1){o.q+=\' 4K\';1e.6g=14.6f(o)[0]-Q.6e}},6d:6(1e){1e.o.B.2D=1e.o.B.4L=1e.o.B.6c=\'0\';o.32=L;o.q=o.q.S(\' 4K\',\'\')}});7(o.4.G(\'U\')>=0){2I.4J(o.4,{4I:\'k\',4H:\'1l\',4G:6(c,9){2.2G(9)}})}},34:6(){3 2J=6b.6a;3 i=2J.e;C(i--){3 1e=2J[i].o;7(1e.4.G(\'9\')>=0||1e.4.G(\'h\')>=0)2J[i].69()}3 3t=2I.68;i=3t.e;C(i--){3 2H=3t[i].o;7(2H.4.G(\'9\')>=0||2H.4.G(\'h\')>=0)2I.67(2H.4)}},2G:6(1i){3 9=n;7(1i!=\'3s\'){9=2.1a(1M(1i.4.S(\'U\',\'\')))}3 K=2.k;3 i=K.e;3 2F=[];3 2E=[];C(i--){7(K[i].g==\'h\'){7(2.4F(K[i],9))2F.1n(K[i].4)}y{7(2.4E(K[i],9))2E.1n(K[i].4)}}7(2F.e==0&&2E.e==0)b;2.1k();2.1J();1V(6(){1W.2G(2F,2E,(9?9.4:\'3s\'))},2S)},4F:6(h,9){7(9&&h.J==9.4||!9&&h.J==n)b l;h.J=(9?9.4:n);3 R=8(\'1l\');7(9)R=8(\'11\'+9.4+\'2c\');14.3r(R,{66:8(\'Z\'+h.4)});2.2j(h);b L},4E:6(9,1d){7(1d&&1d.3T(9))b;7(1d&&9.J==1d.4||!1d&&9.J==n)b l;9.J=(1d?1d.4:n);3 R=8(\'1l\');7(1d)R=8(\'11\'+1d.4+\'2c\');14.3r(R,{2D:8(\'11\'+9.4)});14.3r(R,{2D:8(\'U\'+9.4)});2.3p(9,1d);b L},3p:6(9,3q){9.12=(3q?3q.12+1:0);2.3o(9);3 1G=27(8(\'11\'+9.4+\'2c\'));3 i=1G.e;C(i>0){3 o=1G[--i];7(o.4.G(\'U\')>=0){3 f=2.1a(1M(o.4.3e(\'8\')[1]));2.3p(f,9)}}},3o:6(9){3 12=9.12;3 H=(17?65:4C);3 1F=(12)*(17?10:24);3 1h=1F+(17?10:24);3 1i=8(\'64\'+9.4);1i.B.2C=1F+\'1x\';1i.B.H=(17?H-1F-(12+1)*10:H-1F)+\'1x\';3 r=27(8(\'11\'+9.4+\'2c\'));3 i=r.e;C(i--){3 c=r[i];7(c.4.G(\'Z\')>=0){3 1y=8(\'4D\'+c.4.S(\'Z\',\'\'));1y.B.2C=(17?1h+10:1h+15)+\'1x\';1y.B.H=(17?H-1h-(12+1)*10:H-1h-20)+\'1x\'}y 7(c.4.G(\'11\')>=0){2.3o(2.1a(1M(c.4.S(\'11\',\'\'))))}}},2j:6(h,3n){3 1y=8(\'4D\'+h.4);7(!h.J||3n){1y.B.2C=(17?\'63\':\'62\');1y.B.H=(17?\'61\':\'60\');b}3 12=h.J&&!3n?h.R().12:0;3 H=(17?5Z:4C);3 1h=(12+1)*(17?10:24);1y.B.2C=(17?1h+10:1h+15)+\'1x\';1y.B.H=(17?H-1h-(12+1)*10:H-1h-20)+\'1x\'},1k:6(){7(!2.N)2.1J();3 K=2.N,1w=l,5Y=l,i=0,A;C(i<K.e){A=K[i];7(!5X(A)){i++;3f}7(1w&&A.q.G(\'1w\')<0)A.q+=\' 1w\';y 7(!1w)A.q=A.q.S(\' 1w\',\'\');1w=!1w;i++}},5W:6(M){3 c=8(\'Z\'+M);c.1p.3j(c);2.N=n;2.r=2.r.1c(6(m){b m.4!=M});2.k=2.k.1c(6(c){b(c.g==\'h\'&&c.4!=M||c.g!=\'h\')});2.1k();2.1R()},5V:6(F){3 c=8(\'U\'+F);c.1p.3j(c);2.N=n;c=8(\'11\'+F);3 3m=3i(c,6(o){b o.4&&/^Z(\\d)+$/.2z(o.4)});3 i=3m.e;C(i--){3 M=1M(3m[i].4.S(\'Z\',\'\'));2.r=2.r.1c(6(m){b m.4!=M});2.k=2.k.1c(6(c){b(c.g==\'h\'&&c.4!=M||c.g!=\'h\')})}3 3l=3i(c,6(o){b o.4&&/^U(\\d)+$/.2z(o.4)});i=3l.e;C(i--){3 3k=1M(3l[i].4.S(\'U\',\'\'));2.u=2.u.1c(6(f){b f.4!=3k});2.k=2.k.1c(6(c){b(c.g==\'9\'&&c.4!=3k||c.g!=\'9\')})}c.1p.3j(c);2.u=2.u.1c(6(f){b f.4!=F});2.k=2.k.1c(6(c){b(c.g==\'9\'&&c.4!=F||c.g!=\'9\')});2.1k();2.1R()},5U:6(F){2.2A(8(\'U\'+F));7(8(\'11\'+F)){2.2B(8(\'11\'+F+\'2c\'))}2.1k();2.N=n},5T:6(M){2.2A(8(\'Z\'+M));2.1k();2.N=n},1J:6(){2.N=3i(8(\'1l\'),6(c){b c.4&&(/^U(\\d)+$/.2z(c.4)||/^Z(\\d)+$/.2z(c.4))})},4B:6(){3 K=2.k;3 i=K.e;C(i--){3 c=8(K[i].g+\'8\'+K[i].4);c.q=c.q.S(\' k\',\'\')}2.k=[]},4A:6(4z,4y){3 3h=2.N;3 2y=[];3 2b=l,3g=l;3 i=0;C(i<3h.e){3 2a=3h[i].4;7(2a==4z.4||2a==4y.4){7(2b)3g=L;y{i++;2b=L;3f}}7(2b&&3g)5S;7(!2b){i++;3f}3 3d=1M(2a.3e(\'8\')[1]);7(2a.G(\'U\')>=0){2y.1n(2.1a(3d))}y{2y.1n(2.1K(3d))}i++}b 2y},1K:6(4){3 i=2.r.e;3 19=n;C(i--&&!19){7(2.r[i].4==4)19=2.r[i]}b 19},1a:6(4){3 i=2.u.e;3 19=n;C(i--&&!19){7(2.u[i].4==4)19=2.u[i]}b 19},5R:6(){3 i=2.k.e;C(i--){3 A=2.k[i];3 c=8(A.g+\'8\'+A.4);7(c&&c.q.G(\'k\')<0)c.q+=\' k\';7(!c)2.k=2.k.39(6(2x){b(2x.g==A.g&&2x.4!=A.4||2x.g!=A.g)})}},1R:6(){3 2w=8(\'1l\');3 4x=2w.5Q;7(4x>5P){2.2h=L;2w.q=\'2h\'}y{2.2h=l;2w.q=\'\'}}};2.t={3a:[\'3b\'],3c:[\'3b\',\'4w\',\'4v\'],2v:6(1L){7(2.k.e>1)b;7(2.v){7(2.v.g==\'h\')2.t[1L+\'3Y\'](2.v.4);y 7(!2.t.3a.4u(1L)){2.t[1L+\'3U\'](2.v.4)}y b l}y{E.1E.4r(\'4q\'.j(),5)}},5O:6(){2.34();E.t.V.2t(\'4t\',2.v.4,L)},4s:6(){7(2.k==0){E.1E.4r(\'4q\'.j(),5);b}3 K=2.k;3 i=K.e;3 r=[];3 u=[];C(i--){7(K[i].g==\'h\')r.1n(K[i]);y u.1n(K[i])}3 2u=6(){1u.2X();3 1r={r:r.39(6(h){b h.4}).4p(\',\'),u:u.39(6(9){b 9.4}).4p(\',\')};1N 3E.3D(\'/5N/5M\',{5L:L,5K:L,3C:1r});2.k=[];b l}.O(n);4o.5J();7(r.e==1&&u.e==0){3 h=2.k[0];1u.26(\'<1g>\'+\'5I\'.j()+\'</1g><p>\'+\'4n\'.j()+\' "\'+h.13+\'".<37/>\'+\'36\'.j()+\'</p>\',{25:\'23\',22:2u,21:"35",1Z:1m.1t(E.1E.1Y(),{1D:2n,H:1X})})}y 7(r.e==0&&u.e==1){3 9=2.k[0];1u.26(\'<1g>\'+\'5H\'.j()+\'</1g><p>\'+\'4n\'.j()+\' "\'+9.13.5G()+\'".<37/></p>\'+\'<p D="38">\'+\'5F\'.j()+\' \'+\'36\'.j()+\'</p>\',{25:\'23\',22:2u,21:"35",1Z:1m.1t(E.1E.1Y(),{1D:2n,H:1X})})}y{3 29=\'\';1v(3 i=0,16=r.e;i<16;i++){7(i!=0)29+=\', \';29+=\'"\'+r[i].13+\'"\'}3 28=\'\';1v(i=0,16=u.e;i<16;i++){7(i!=0)28+=\', \';28+=\'"\'+u[i].13+\'"\'}3 s=\'\';7(r.e==0){s=\'5E\'.j([28])+\'</p><p D="38">\'+\'4m\'.j()}y 7(u.e==0){s=\'5D\'.j([29])}y{s=\'5C\'.j([29,28])+\'</p><p D="38">\'+\'4m\'.j()}1u.26(\'<1g>\'+\'5B\'.j()+\'</1g><p>\'+s+\'.<37/>\'+\'36\'.j()+\'</p>\',{25:\'23\',22:2u,21:"35",1Z:1m.1t(E.1E.1Y(),{1D:5A,H:1X})})}},5z:6(){3 M=(2.v&&2.v.g==\'h\'?2.v.4:n);7(!M)b;2.34();E.t.V.2t(\'4l\',M,L)},5y:6(){3 F=(2.v&&2.v.g==\'9\'?2.v.4:n);7(!F)b;3 9=2.1a(F);3 13=9.13.S(\'"\',\'\\"\');1u.26(\'<1g>\'+\'5x\'.j()+\'</1g>\'+\'<X D="4d" B="1D: 2p;"><X D="4c">\'+\'<2o B="H: 2p;1F-4b:4a" D="49" 1v="9[W]">\'+\'48\'.j()+\':</2o>\'+\'<47 W="9[W]" 4="9[W]" B="H:46" g="s" 18="\'+9.5w+\'""/>\'+\'</X></X>\',{25:\'23\',22:6(F){1W.5v(F,8(\'9[W]\').18);1u.2X()}.O(n,F),21:\'44\'.j(),43:6(){},1Z:1m.1t(E.1E.1Y(),{1D:2n,H:1X})});1V(6(){8(\'9[W]\').18=13;7(8(\'9[W]\').2Z)8(\'9[W]\').2Z()},42)},4k:6(){3 33=27(8(\'4j\'))[0];3 1j=1b.1o.31+\'//\'+1b.1o.30+\'/r/1N\'+(2.v&&2.v.g==\'9\'?\'?2Y=\'+2.v.4:\'\');7(33&&14.5u(33,\'2r\')){3 2q=4h.2s(1j,\'2r\');2q.4g()}y{1b.1o.Y=1j}b l},5t:6(M,4i){3 o=8(\'Z\'+M);7(o.32){o.32=l;b l}3 h=2.1K(M);3 1j=1b.1o.31+\'//\'+1b.1o.30+\'/r/1S/\'+h.2W;7(2.x.1Q&&2.x.1O)1j+=\'?I=\'+2.x.I;7(4i==\'2r\'){3 2q=4h.2s(1j,\'2r\');2q.4g()}y{1b.1o.Y=1j}b l},4f:6(){1b.1o.Y=1b.1o.31+\'//\'+1b.1o.30+\'/5s/5r\'+(2.v&&2.v.g==\'9\'?\'?2Y=\'+2.v.4:\'\');b l},4e:6(){1u.26(\'<1g>\'+\'45\'.j()+\'</1g>\'+\'<X D="4d" B="1D: 2p;"><X D="4c">\'+\'<2o B="H: 2p;1F-4b:4a" D="49" 1v="9[W]">\'+\'48\'.j()+\':</2o>\'+\'<47 W="9[W]" 4="9[W]" B="H:46" g="s" 18="\'+\'45\'.j()+\'""/>\'+\'</X></X>\',{25:\'23\',22:2.t.41.O(n),21:\'44\'.j(),43:6(){},1Z:1m.1t(E.1E.1Y(),{1D:2n,H:1X})});7(!5q)1V("8(\'9[W]\').2Z()",42)},41:6(){2.t.2m();3 1r={};7(2.v&&2.v.g==\'9\')1m.1t(1r,{2Y:2.v.4});7(2.r.e==0&&2.u.e==0)1m.1t(1r,{5p:1});1W.2k(8(\'9[W]\').18,1r);1u.2X()},5o:6(M){2.t.2m();E.t.V.40(M)},5n:6(F){2.t.2m();1W.5m(F)},2m:6(){3 1C=1b.3Z(\'1C\')[0];1C.q+=\' 2i\'},5l:6(){3 1C=1b.3Z(\'1C\')[0];1C.q=1C.q.S(\' 2i\',\'\')}};3 3Y=2U.2k({2T:6(P){z.4=P.4;z.2W=P.2W;z.3X=P.3X;z.13=P.13;z.J=P.J;z.2l=!!P.2l;z.3W=!!P.3W;z.2V=!!P.2V;z.3V=!!P.3V;z.g=\'h\';2.r.1n(z);2.N=n},R:6(){b(z.J?2.1a(z.J):n)}});3 3U=2U.2k({2T:6(P){z.4=P.4;z.13=P.13;z.1B=P.1B;z.12=P.12;z.J=P.J;z.g=\'9\';2.u.1n(z);2.N=n},R:6(){b(z.J?2.1a(z.J):n)},3T:6(R){7(!z.J)b l;3 9=1m.5k(z);C(9.J){7(9.J==R.4)b L;9=9.R()}b l}});1m.1t(2,{3N:6(){3 i=2.r.e;C(i--){2.2j(2.r[i],L)}i=2.u.e;C(i--){2R(8(\'U\'+2.u[i].4))}2.3S=L},3J:6(){3 i=2.r.e;C(i--){2.2j(2.r[i],l)}i=2.u.e;C(i--){1S(8(\'U\'+2.u[i].4))}2.3S=l},3M:6(){3 u=2.u;3 i=u.e;C(i--){8(\'11\'+u[i].4).B.1s=\'\'}},3I:6(){3 u=2.u;3 i=u.e;C(i--){7(!u[i].1B)8(\'11\'+u[i].4).B.1s=\'1P\'}},x:{1Q:l,I:\'\',2O:n,2Q:\'\',1O:l,3R:6(3Q){E.t.5j(3Q,6(){2.x.2O=1N 3P({5i:2.x.1T.O(n)});1V(6(){2.x.2g()},2S)},6(){b(5h 3P!=\'5g\')});8(\'5f\').5e=6(){b l}.O(n);$(\'I\').5d(\'5c\',6(){7(8(\'I\').18.e==\'\'){2.x.1T()}y{2.x.3O()}}.O(n));8(\'5b\').T=2.x.3F.O(n);8(\'5a\').T=2.x.1T.O(n);8(\'59\').T=2.x.1T.O(n)},3O:6(){7(2.x.I==8(\'I\').18.1U())b;2.x.I=8(\'I\').18.1U();2.x.1O=l;2.x.2P();2.x.3L(8(\'I\').18);8(\'2f\').q=8(\'2f\').q.S(\' 2i\',\'\');2.x.2g();b l},2P:6(){7(!2.x.1Q){2.3N();2.x.1Q=L;2.x.1O=l;2.3M()}8(\'2f\').q+=\' 2i\'},3L:6(s){3 r=2.r;3 i=r.e;3 19=0;s=s.1U();C(i--){7(r[i].13.1U().G(s)>=0){1S(8(\'Z\'+r[i].4));19++}y{2R(8(\'Z\'+r[i].4))}}7(19<15)8(\'1l\').q=\'\';y 8(\'1l\').q=\'2h\';2.1k();8(\'58\').3K=\'57\'.j([s]);8(\'3H\').B.1s=\'1P\';8(\'3G\').B.1s=\'\'},56:6(){7(!2.N)2.1J();3 1I=2.N;3 i=1I.e;C(i--){2R(1I[i])}},1T:6(){8(\'I\').18=\'\';2.3J();2.3I();7(!2.N)2.1J();3 1I=2.N;3 i=1I.e;C(i--){1S(1I[i])}2.1k();2.1R();2.x.1Q=l;2.x.I=\'\';8(\'3H\').B.1s=\'1P\';8(\'3G\').B.1s=\'1P\';2.x.2g()},3F:6(){3 I=2.x.I;2.x.1O=L;8(\'I\').18=I;3 1j=\'/r/55\';3 1r={2Q:2.x.2Q,I:I};1N 3E.3D(1j,{3C:1r});2.x.2P();b l},2g:6(){2.x.2O.54(\'I\',\'2f\')}}});',62,416,'||mapList|var|id||function|if|_|folder||return|el||length||type|map||tr|selected|false||null|element||className|maps|text|utils|folders|lastSelected|li|search|else|this|item|style|while|class|MindMeister|folderId|indexOf|width|query|parent_id|items|true|mapId|flatList|bind|data|event|parent|replace|onclick|folder_|dialogs|name|div|href|map_||children_|rank|title|Element||len|isIE6|value|found|getFolder|document|select|parentFolder|drag|cm_link|h2|childrenMargin|folderEl|url|updateGrid|maps_body|Object|push|location|parentNode|actionButton|params|display|extend|Dialog|for|odd|px|mapEl|actions|span|is_open|body|height|ui|margin|children|bindOnClick|elements|updateFlatListing|getMap|action|parseInt|new|full|none|on|setMapsBodyHeight|show|clear|toLowerCase|setTimeout|Folders|360|dialogDefaultOptions|windowParameters||okLabel|ok|button||buttonClass|confirm|childElements|folderNames|mapNames|elId|found1|_list|disabled|partition|srch_clear|update|scrollable|loading|updateMapIndentation|create|mine|showLoading|180|label|auto|newWindow|_blank|open|showAjaxDialog|deleteFunction|callAction|mapsBody|it|ranged|test|makeElementDraggable|makeParentDraggable|marginLeft|top|folderIds|mapIds|move|drop|Droppables|draggables|childrenEl|selectItem|js_delete|super|applesearch|prepare|order|hide|100|initialize|Class|upgrade|root_id|closeInfo|folder_id|activate|host|protocol|has_just_been_dragged|link|disableDragDrop|OK|js_do_you_want_to_continue|br|alert|collect|foldersNotAllowedAction|share|nonMultiselectActions|entityId|split|continue|found2|allItems|descendants|removeChild|fId|folderEls|mapEls|ignoreParent|updateFolderIndentation|updateRankAndIndentation|newParent|insert|root|droppables|_map|selectedFolder|selectedMap|ctrl|newlySelected|separator|offset|pos|parameters|Request|Ajax|_doFull|search_info_content|search_info_content_full|updateOpenFolders|indentMaps|innerHTML|doFilter|openAllFolders|showFlatMaps|_do|AppleSearch|searchFile|init|flatOn|isDescendantOf|Folder|subshare|published|author|Map|getElementsByTagName|doCopyMap|createFolder|250|onload|js_ok|js_new_folder|200px|input|js_name|dlg|30px|right|tabcontent|tabcontainer|newFolder|importMap|focus|window|target|create_new_map|newMap|properties_general|js_delete_folders_warning|js_you_are_about_to_delete|ContextMenu|join|js_please_select_something_first|showFlashMessage|deleteAll|properties_share|include|properties|duplicate|mapsBodyHeight|el2|el1|getRange|unSelectAll|350|map_entity_|moveFolder|moveMap|onDrop|scroll|hoverclass|add|dragging|left|totalSelected|list|root_folder|unselectItem|to|from|shiftKey|ul|js_export|js_share|js_preview|dialogPreviewMap|dialogWithdrawFromMap|dialogDeleteMap|js_duplicate|js_open|map_id|getItems|onChange|search_maps|hideAll|js_displaying_maps_that_contain_in_title_s|search_info|clear_search_full|clear_search|do_full_search|keyup|observe|onsubmit|searchform|undefined|typeof|onClickClearBtn|loadScript|clone|hideLoading|copy|duplicateFolder|duplicateMap|refresh|isIE|show_upload|home|openMap|hasClassName|rename|textContent|js_edit_folder|propertiesFolder|propertiesMap|300|js_delete_items|js_you_are_about_to_delete_maps_s_and_folders_s|js_you_are_about_to_delete_maps_s|js_you_are_about_to_delete_folders_s|js_delete_folder_warning|escapeHTML|js_delete_folder|js_delete_mind_map|closeAll|evalScripts|asynchronous|bulk_delete|dialog|shareMap|500|scrollHeight|updateSelection|break|afterMapAdd|afterFolderAdd|removeFolder|removeMap|isVisible|closed|325|329px|330px|16px|8px|folder_entity_|335|bottom|remove|drops|destroy|drags|Draggables|zIndex|onEnd|clientX|viewportOffset|originalScrollLeft|onStart|ghosting|failure|revert|Draggable|enableDragDrop|toggle|toggleFolder|ctrlKey|metaKey|isMac|properties_map|delete_map|share_map|duplicate_map|create_new_folder|import_map|js_properties|dialogExportMap|doShowMap|cumulativeOffset|Position|img|tagName|Event|mapmenu|MapMenu'.split('|'),0,{}))
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)d[e(c)]=k[c]||e(c);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('F s={H:6(7,d){5 4.3(\'/2/H\',{8:o.n(d,{7:7})})},1g:6(a,7){5 4.3(\'/2/e/\'+a,{8:{7:7}})},1f:6(a,G){5 4.3(\'/2/e/\'+a,{8:{1e:G?1:0}})},B:6(E,D,A){F d={1d:E.C(\',\'),2:D.C(\',\')};5 4.3(\'/2/B/\'+A,{8:d})},z:6(c){5 4.3(\'/2/z/\'+c)},y:6(c){5 4.3(\'/2/y/\'+c)},f:{1c:6(b){1b.1a(\'<x>\'+\'19\'.9()+\'</x>\'+\'<p>\'+\'18\'.9()+\'</p>\'+\'<p u="t-g:0.17">\'+\'16\'.9()+\'</p>\'+\'<w a="15" v="14"></w>\'+\'<p v=13 u="k:12;11-g:10;t-g:0;Z-l:Y">\'+\'X\'.9()+\'</p>\',{W:\'V\',U:s.f.i.r(q,b),T:6(){m.S.R(\'Q\',1,j)}.r(q),P:\'O\'.9(),N:o.n(m.M.L(),{l:K,k:j})});5 4.3(\'/2/f\')},i:6(b){J(b){5 4.3(\'/2/h\',{8:{e:1}})}I{5 4.3(\'/2/h\')}}}};',62,79,'||folders|Request|Ajax|new|function|name|parameters|tr|id|update_listing|folderId|params|update|wizard|top|do_wizard|_do|500|width|height|MindMeister|extend|Object||null|bind|Folders|margin|style|class|div|h2|copy|destroy|targetId|move|join|folderIds|mapIds|var|isOpen|create|else|if|350|dialogDefaultOptions|ui|windowParameters|folder_wizard_convert|okLabel|hide_folder_wizard|setCookie|utils|cancel|ok|button|buttonClass|folder_wizard_info|16px|line|none|border|auto|info|loading|new_folders_table|folder_wizard_text2|5em|folder_wizard_text|folder_wizard_title|confirm|Dialog|init|maps|is_open|toggle|rename'.split('|'),0,{}))
window._mm_file_versions = window._mm_file_versions || {}; _mm_file_versions['bin/core.js'] = '13737M';
