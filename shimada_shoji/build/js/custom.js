/**
 * Resize function without multiple trigger
 * 
 * Usage:
 * $(window).smartresize(function(){  
 *     // code here
 * });
 */
(function($,sr){
    // debouncing function from John Hann
    // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
    var debounce = function (func, threshold, execAsap) {
      var timeout;

        return function debounced () {
            var obj = this, args = arguments;
            function delayed () {
                if (!execAsap)
                    func.apply(obj, args); 
                timeout = null; 
            }

            if (timeout)
                clearTimeout(timeout);
            else if (execAsap)
                func.apply(obj, args);

            timeout = setTimeout(delayed, threshold || 100); 
        };
    };

    // smartresize 
    jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');
/*!
 * validate.js 0.12.0
 *
 * (c) 2013-2017 Nicklas Ansman, 2013 Wrapp
 * Validate.js may be freely distributed under the MIT license.
 * For all details and documentation:
 * http://validatejs.org/
 */

(function(exports, module, define) {
    "use strict";

    // The main function that calls the validators specified by the constraints.
    // The options are the following:
    //   - format (string) - An option that controls how the returned value is formatted
    //     * flat - Returns a flat array of just the error messages
    //     * grouped - Returns the messages grouped by attribute (default)
    //     * detailed - Returns an array of the raw validation data
    //   - fullMessages (boolean) - If `true` (default) the attribute name is prepended to the error.
    //
    // Please note that the options are also passed to each validator.
    var validate = function(attributes, constraints, options) {
        options = v.extend({}, v.options, options);

        var results = v.runValidations(attributes, constraints, options),
            attr, validator;

        if (results.some(function(r) { return v.isPromise(r.error); })) {
            throw new Error("Use validate.async if you want support for promises");
        }
        return validate.processValidationResults(results, options);
    };

    var v = validate;

    // Copies over attributes from one or more sources to a single destination.
    // Very much similar to underscore's extend.
    // The first argument is the target object and the remaining arguments will be
    // used as sources.
    v.extend = function(obj) {
        [].slice.call(arguments, 1).forEach(function(source) {
            for (var attr in source) {
                obj[attr] = source[attr];
            }
        });
        return obj;
    };

    v.extend(validate, {
        // This is the version of the library as a semver.
        // The toString function will allow it to be coerced into a string
        version: {
            major: 0,
            minor: 12,
            patch: 0,
            metadata: null,
            toString: function() {
                var version = v.format("%{major}.%{minor}.%{patch}", v.version);
                if (!v.isEmpty(v.version.metadata)) {
                    version += "+" + v.version.metadata;
                }
                return version;
            }
        },

        // Below is the dependencies that are used in validate.js

        // The constructor of the Promise implementation.
        // If you are using Q.js, RSVP or any other A+ compatible implementation
        // override this attribute to be the constructor of that promise.
        // Since jQuery promises aren't A+ compatible they won't work.
        Promise: typeof Promise !== "undefined" ? Promise : /* istanbul ignore next */ null,

        EMPTY_STRING_REGEXP: /^\s*$/,

        // Runs the validators specified by the constraints object.
        // Will return an array of the format:
        //     [{attribute: "<attribute name>", error: "<validation result>"}, ...]
        runValidations: function(attributes, constraints, options) {
            var results = [],
                attr, validatorName, value, validators, validator, validatorOptions, error;

            if (v.isDomElement(attributes) || v.isJqueryElement(attributes)) {
                attributes = v.collectFormValues(attributes);
            }

            // Loops through each constraints, finds the correct validator and run it.
            for (attr in constraints) {
                value = v.getDeepObjectValue(attributes, attr);
                // This allows the constraints for an attribute to be a function.
                // The function will be called with the value, attribute name, the complete dict of
                // attributes as well as the options and constraints passed in.
                // This is useful when you want to have different
                // validations depending on the attribute value.
                validators = v.result(constraints[attr], value, attributes, attr, options, constraints);

                for (validatorName in validators) {
                    validator = v.validators[validatorName];

                    if (!validator) {
                        error = v.format("Unknown validator %{name}", { name: validatorName });
                        throw new Error(error);
                    }

                    validatorOptions = validators[validatorName];
                    // This allows the options to be a function. The function will be
                    // called with the value, attribute name, the complete dict of
                    // attributes as well as the options and constraints passed in.
                    // This is useful when you want to have different
                    // validations depending on the attribute value.
                    validatorOptions = v.result(validatorOptions, value, attributes, attr, options, constraints);
                    if (!validatorOptions) {
                        continue;
                    }
                    results.push({
                        attribute: attr,
                        value: value,
                        validator: validatorName,
                        globalOptions: options,
                        attributes: attributes,
                        options: validatorOptions,
                        error: validator.call(validator,
                            value,
                            validatorOptions,
                            attr,
                            attributes,
                            options)
                    });
                }
            }

            return results;
        },

        // Takes the output from runValidations and converts it to the correct
        // output format.
        processValidationResults: function(errors, options) {
            errors = v.pruneEmptyErrors(errors, options);
            errors = v.expandMultipleErrors(errors, options);
            errors = v.convertErrorMessages(errors, options);

            var format = options.format || "grouped";

            if (typeof v.formatters[format] === 'function') {
                errors = v.formatters[format](errors);
            } else {
                throw new Error(v.format("Unknown format %{format}", options));
            }

            return v.isEmpty(errors) ? undefined : errors;
        },

        // Runs the validations with support for promises.
        // This function will return a promise that is settled when all the
        // validation promises have been completed.
        // It can be called even if no validations returned a promise.
        async: function(attributes, constraints, options) {
            options = v.extend({}, v.async.options, options);

            var WrapErrors = options.wrapErrors || function(errors) {
                return errors;
            };

            // Removes unknown attributes
            if (options.cleanAttributes !== false) {
                attributes = v.cleanAttributes(attributes, constraints);
            }

            var results = v.runValidations(attributes, constraints, options);

            return new v.Promise(function(resolve, reject) {
                v.waitForResults(results).then(function() {
                    var errors = v.processValidationResults(results, options);
                    if (errors) {
                        reject(new WrapErrors(errors, options, attributes, constraints));
                    } else {
                        resolve(attributes);
                    }
                }, function(err) {
                    reject(err);
                });
            });
        },

        single: function(value, constraints, options) {
            options = v.extend({}, v.single.options, options, {
                format: "flat",
                fullMessages: false
            });
            return v({ single: value }, { single: constraints }, options);
        },

        // Returns a promise that is resolved when all promises in the results array
        // are settled. The promise returned from this function is always resolved,
        // never rejected.
        // This function modifies the input argument, it replaces the promises
        // with the value returned from the promise.
        waitForResults: function(results) {
            // Create a sequence of all the results starting with a resolved promise.
            return results.reduce(function(memo, result) {
                // If this result isn't a promise skip it in the sequence.
                if (!v.isPromise(result.error)) {
                    return memo;
                }

                return memo.then(function() {
                    return result.error.then(function(error) {
                        result.error = error || null;
                    });
                });
            }, new v.Promise(function(r) { r(); })); // A resolved promise
        },

        // If the given argument is a call: function the and: function return the value
        // otherwise just return the value. Additional arguments will be passed as
        // arguments to the function.
        // Example:
        // ```
        // result('foo') // 'foo'
        // result(Math.max, 1, 2) // 2
        // ```
        result: function(value) {
            var args = [].slice.call(arguments, 1);
            if (typeof value === 'function') {
                value = value.apply(null, args);
            }
            return value;
        },

        // Checks if the value is a number. This function does not consider NaN a
        // number like many other `isNumber` functions do.
        isNumber: function(value) {
            return typeof value === 'number' && !isNaN(value);
        },

        // Returns false if the object is not a function
        isFunction: function(value) {
            return typeof value === 'function';
        },

        // A simple check to verify that the value is an integer. Uses `isNumber`
        // and a simple modulo check.
        isInteger: function(value) {
            return v.isNumber(value) && value % 1 === 0;
        },

        // Checks if the value is a boolean
        isBoolean: function(value) {
            return typeof value === 'boolean';
        },

        // Uses the `Object` function to check if the given argument is an object.
        isObject: function(obj) {
            return obj === Object(obj);
        },

        // Simply checks if the object is an instance of a date
        isDate: function(obj) {
            return obj instanceof Date;
        },

        // Returns false if the object is `null` of `undefined`
        isDefined: function(obj) {
            return obj !== null && obj !== undefined;
        },

        // Checks if the given argument is a promise. Anything with a `then`
        // function is considered a promise.
        isPromise: function(p) {
            return !!p && v.isFunction(p.then);
        },

        isJqueryElement: function(o) {
            return o && v.isString(o.jquery);
        },

        isDomElement: function(o) {
            if (!o) {
                return false;
            }

            if (!o.querySelectorAll || !o.querySelector) {
                return false;
            }

            if (v.isObject(document) && o === document) {
                return true;
            }

            // http://stackoverflow.com/a/384380/699304
            /* istanbul ignore else */
            if (typeof HTMLElement === "object") {
                return o instanceof HTMLElement;
            } else {
                return o &&
                    typeof o === "object" &&
                    o !== null &&
                    o.nodeType === 1 &&
                    typeof o.nodeName === "string";
            }
        },

        isEmpty: function(value) {
            var attr;

            // Null and undefined are empty
            if (!v.isDefined(value)) {
                return true;
            }

            // functions are non empty
            if (v.isFunction(value)) {
                return false;
            }

            // Whitespace only strings are empty
            if (v.isString(value)) {
                return v.EMPTY_STRING_REGEXP.test(value);
            }

            // For arrays we use the length property
            if (v.isArray(value)) {
                return value.length === 0;
            }

            // Dates have no attributes but aren't empty
            if (v.isDate(value)) {
                return false;
            }

            // If we find at least one property we consider it non empty
            if (v.isObject(value)) {
                for (attr in value) {
                    return false;
                }
                return true;
            }

            return false;
        },

        // Formats the specified strings with the given values like so:
        // ```
        // format("Foo: %{foo}", {foo: "bar"}) // "Foo bar"
        // ```
        // If you want to write %{...} without having it replaced simply
        // prefix it with % like this `Foo: %%{foo}` and it will be returned
        // as `"Foo: %{foo}"`
        format: v.extend(function(str, vals) {
            if (!v.isString(str)) {
                return str;
            }
            return str.replace(v.format.FORMAT_REGEXP, function(m0, m1, m2) {
                if (m1 === '%') {
                    return "%{" + m2 + "}";
                } else {
                    return String(vals[m2]);
                }
            });
        }, {
            // Finds %{key} style patterns in the given string
            FORMAT_REGEXP: /(%?)%\{([^\}]+)\}/g
        }),

        // "Prettifies" the given string.
        // Prettifying means replacing [.\_-] with spaces as well as splitting
        // camel case words.
        prettify: function(str) {
            if (v.isNumber(str)) {
                // If there are more than 2 decimals round it to two
                if ((str * 100) % 1 === 0) {
                    return "" + str;
                } else {
                    return parseFloat(Math.round(str * 100) / 100).toFixed(2);
                }
            }

            if (v.isArray(str)) {
                return str.map(function(s) { return v.prettify(s); }).join(", ");
            }

            if (v.isObject(str)) {
                return str.toString();
            }

            // Ensure the string is actually a string
            str = "" + str;

            return str
                // Splits keys separated by periods
                .replace(/([^\s])\.([^\s])/g, '$1 $2')
                // Removes backslashes
                .replace(/\\+/g, '')
                // Replaces - and - with space
                .replace(/[_-]/g, ' ')
                // Splits camel cased words
                .replace(/([a-z])([A-Z])/g, function(m0, m1, m2) {
                    return "" + m1 + " " + m2.toLowerCase();
                })
                .toLowerCase();
        },

        stringifyValue: function(value, options) {
            var prettify = options && options.prettify || v.prettify;
            return prettify(value);
        },

        isString: function(value) {
            return typeof value === 'string';
        },

        isArray: function(value) {
            return {}.toString.call(value) === '[object Array]';
        },

        // Checks if the object is a hash, which is equivalent to an object that
        // is neither an array nor a function.
        isHash: function(value) {
            return v.isObject(value) && !v.isArray(value) && !v.isFunction(value);
        },

        contains: function(obj, value) {
            if (!v.isDefined(obj)) {
                return false;
            }
            if (v.isArray(obj)) {
                return obj.indexOf(value) !== -1;
            }
            return value in obj;
        },

        unique: function(array) {
            if (!v.isArray(array)) {
                return array;
            }
            return array.filter(function(el, index, array) {
                return array.indexOf(el) == index;
            });
        },

        forEachKeyInKeypath: function(object, keypath, callback) {
            if (!v.isString(keypath)) {
                return undefined;
            }

            var key = "",
                i, escape = false;

            for (i = 0; i < keypath.length; ++i) {
                switch (keypath[i]) {
                    case '.':
                        if (escape) {
                            escape = false;
                            key += '.';
                        } else {
                            object = callback(object, key, false);
                            key = "";
                        }
                        break;

                    case '\\':
                        if (escape) {
                            escape = false;
                            key += '\\';
                        } else {
                            escape = true;
                        }
                        break;

                    default:
                        escape = false;
                        key += keypath[i];
                        break;
                }
            }

            return callback(object, key, true);
        },

        getDeepObjectValue: function(obj, keypath) {
            if (!v.isObject(obj)) {
                return undefined;
            }

            return v.forEachKeyInKeypath(obj, keypath, function(obj, key) {
                if (v.isObject(obj)) {
                    return obj[key];
                }
            });
        },

        // This returns an object with all the values of the form.
        // It uses the input name as key and the value as value
        // So for example this:
        // <input type="text" name="email" value="foo@bar.com" />
        // would return:
        // {email: "foo@bar.com"}
        collectFormValues: function(form, options) {
            var values = {},
                i, j, input, inputs, option, value;

            if (v.isJqueryElement(form)) {
                form = form[0];
            }

            if (!form) {
                return values;
            }

            options = options || {};

            inputs = form.querySelectorAll("input[name], textarea[name]");
            for (i = 0; i < inputs.length; ++i) {
                input = inputs.item(i);

                if (v.isDefined(input.getAttribute("data-ignored"))) {
                    continue;
                }

                name = input.name.replace(/\./g, "\\\\.");
                value = v.sanitizeFormValue(input.value, options);
                if (input.type === "number") {
                    value = value ? +value : null;
                } else if (input.type === "checkbox") {
                    if (input.attributes.value) {
                        if (!input.checked) {
                            value = values[name] || null;
                        }
                    } else {
                        value = input.checked;
                    }
                } else if (input.type === "radio") {
                    if (!input.checked) {
                        value = values[name] || null;
                    }
                }
                values[name] = value;
            }

            inputs = form.querySelectorAll("select[name]");
            for (i = 0; i < inputs.length; ++i) {
                input = inputs.item(i);
                if (v.isDefined(input.getAttribute("data-ignored"))) {
                    continue;
                }

                if (input.multiple) {
                    value = [];
                    for (j in input.options) {
                        option = input.options[j];
                        if (option && option.selected) {
                            value.push(v.sanitizeFormValue(option.value, options));
                        }
                    }
                } else {
                    var _val = typeof input.options[input.selectedIndex] !== 'undefined' ? input.options[input.selectedIndex].value : '';
                    value = v.sanitizeFormValue(_val, options);
                }
                values[input.name] = value;
            }

            return values;
        },

        sanitizeFormValue: function(value, options) {
            if (options.trim && v.isString(value)) {
                value = value.trim();
            }

            if (options.nullify !== false && value === "") {
                return null;
            }
            return value;
        },

        capitalize: function(str) {
            if (!v.isString(str)) {
                return str;
            }
            return str[0].toUpperCase() + str.slice(1);
        },

        // Remove all errors who's error attribute is empty (null or undefined)
        pruneEmptyErrors: function(errors) {
            return errors.filter(function(error) {
                return !v.isEmpty(error.error);
            });
        },

        // In
        // [{error: ["err1", "err2"], ...}]
        // Out
        // [{error: "err1", ...}, {error: "err2", ...}]
        //
        // All attributes in an error with multiple messages are duplicated
        // when expanding the errors.
        expandMultipleErrors: function(errors) {
            var ret = [];
            errors.forEach(function(error) {
                // Removes errors without a message
                if (v.isArray(error.error)) {
                    error.error.forEach(function(msg) {
                        ret.push(v.extend({}, error, { error: msg }));
                    });
                } else {
                    ret.push(error);
                }
            });
            return ret;
        },

        // Converts the error mesages by prepending the attribute name unless the
        // message is prefixed by ^
        convertErrorMessages: function(errors, options) {
            options = options || {};

            var ret = [],
                prettify = options.prettify || v.prettify;
            errors.forEach(function(errorInfo) {
                var error = v.result(errorInfo.error,
                    errorInfo.value,
                    errorInfo.attribute,
                    errorInfo.options,
                    errorInfo.attributes,
                    errorInfo.globalOptions);

                if (!v.isString(error)) {
                    ret.push(errorInfo);
                    return;
                }

                if (error[0] === '^') {
                    error = error.slice(1);
                } else if (options.fullMessages !== false) {
                    error = v.capitalize(prettify(errorInfo.attribute)) + " " + error;
                }
                error = error.replace(/\\\^/g, "^");
                error = v.format(error, {
                    value: v.stringifyValue(errorInfo.value, options)
                });
                ret.push(v.extend({}, errorInfo, { error: error }));
            });
            return ret;
        },

        // In:
        // [{attribute: "<attributeName>", ...}]
        // Out:
        // {"<attributeName>": [{attribute: "<attributeName>", ...}]}
        groupErrorsByAttribute: function(errors) {
            var ret = {};
            errors.forEach(function(error) {
                var list = ret[error.attribute];
                if (list) {
                    list.push(error);
                } else {
                    ret[error.attribute] = [error];
                }
            });
            return ret;
        },

        // In:
        // [{error: "<message 1>", ...}, {error: "<message 2>", ...}]
        // Out:
        // ["<message 1>", "<message 2>"]
        flattenErrorsToArray: function(errors) {
            return errors
                .map(function(error) { return error.error; })
                .filter(function(value, index, self) {
                    return self.indexOf(value) === index;
                });
        },

        cleanAttributes: function(attributes, whitelist) {
            function whitelistCreator(obj, key, last) {
                if (v.isObject(obj[key])) {
                    return obj[key];
                }
                return (obj[key] = last ? true : {});
            }

            function buildObjectWhitelist(whitelist) {
                var ow = {},
                    lastObject, attr;
                for (attr in whitelist) {
                    if (!whitelist[attr]) {
                        continue;
                    }
                    v.forEachKeyInKeypath(ow, attr, whitelistCreator);
                }
                return ow;
            }

            function cleanRecursive(attributes, whitelist) {
                if (!v.isObject(attributes)) {
                    return attributes;
                }

                var ret = v.extend({}, attributes),
                    w, attribute;

                for (attribute in attributes) {
                    w = whitelist[attribute];

                    if (v.isObject(w)) {
                        ret[attribute] = cleanRecursive(ret[attribute], w);
                    } else if (!w) {
                        delete ret[attribute];
                    }
                }
                return ret;
            }

            if (!v.isObject(whitelist) || !v.isObject(attributes)) {
                return {};
            }

            whitelist = buildObjectWhitelist(whitelist);
            return cleanRecursive(attributes, whitelist);
        },

        exposeModule: function(validate, root, exports, module, define) {
            if (exports) {
                if (module && module.exports) {
                    exports = module.exports = validate;
                }
                exports.validate = validate;
            } else {
                root.validate = validate;
                if (validate.isFunction(define) && define.amd) {
                    define([], function() { return validate; });
                }
            }
        },

        warn: function(msg) {
            if (typeof console !== "undefined" && console.warn) {
                console.warn("[validate.js] " + msg);
            }
        },

        error: function(msg) {
            if (typeof console !== "undefined" && console.error) {
                console.error("[validate.js] " + msg);
            }
        }
    });

    validate.validators = {
        // Presence validates that the value isn't empty
        presence: function(value, options) {
            options = v.extend({}, this.options, options);
            if (options.allowEmpty !== false ? !v.isDefined(value) : v.isEmpty(value)) {
                return options.message || this.message || "can't be blank";
            }
        },
        length: function(value, options, attribute) {
            // Empty values are allowed
            if (!v.isDefined(value)) {
                return;
            }

            options = v.extend({}, this.options, options);

            var is = options.is,
                maximum = options.maximum,
                minimum = options.minimum,
                tokenizer = options.tokenizer || function(val) { return val; },
                err, errors = [];

            value = tokenizer(value);
            var length = value.length;
            if (!v.isNumber(length)) {
                v.error(v.format("Attribute %{attr} has a non numeric value for `length`", { attr: attribute }));
                return options.message || this.notValid || "has an incorrect length";
            }

            // Is checks
            if (v.isNumber(is) && length !== is) {
                err = options.wrongLength ||
                    this.wrongLength ||
                    "is the wrong length (should be %{count} characters)";
                errors.push(v.format(err, { count: is }));
            }

            if (v.isNumber(minimum) && length < minimum) {
                err = options.tooShort ||
                    this.tooShort ||
                    "is too short (minimum is %{count} characters)";
                errors.push(v.format(err, { count: minimum }));
            }

            if (v.isNumber(maximum) && length > maximum) {
                err = options.tooLong ||
                    this.tooLong ||
                    "is too long (maximum is %{count} characters)";
                errors.push(v.format(err, { count: maximum }));
            }

            if (errors.length > 0) {
                return options.message || errors;
            }
        },
        numericality: function(value, options, attribute, attributes, globalOptions) {
            // Empty values are fine
            if (!v.isDefined(value)) {
                return;
            }

            options = v.extend({}, this.options, options);

            var errors = [],
                name, count, checks = {
                    greaterThan: function(v, c) { return v > c; },
                    greaterThanOrEqualTo: function(v, c) { return v >= c; },
                    equalTo: function(v, c) { return v === c; },
                    lessThan: function(v, c) { return v < c; },
                    lessThanOrEqualTo: function(v, c) { return v <= c; },
                    divisibleBy: function(v, c) { return v % c === 0; }
                },
                prettify = options.prettify ||
                (globalOptions && globalOptions.prettify) ||
                v.prettify;

            // Strict will check that it is a valid looking number
            if (v.isString(value) && options.strict) {
                var pattern = "^-?(0|[1-9]\\d*)";
                if (!options.onlyInteger) {
                    pattern += "(\\.\\d+)?";
                }
                pattern += "$";

                if (!(new RegExp(pattern).test(value))) {
                    return options.message ||
                        options.notValid ||
                        this.notValid ||
                        this.message ||
                        "must be a valid number";
                }
            }

            // Coerce the value to a number unless we're being strict.
            if (options.noStrings !== true && v.isString(value) && !v.isEmpty(value)) {
                value = +value;
            }

            // If it's not a number we shouldn't continue since it will compare it.
            if (!v.isNumber(value)) {
                return options.message ||
                    options.notValid ||
                    this.notValid ||
                    this.message ||
                    "is not a number";
            }

            // Same logic as above, sort of. Don't bother with comparisons if this
            // doesn't pass.
            if (options.onlyInteger && !v.isInteger(value)) {
                return options.message ||
                    options.notInteger ||
                    this.notInteger ||
                    this.message ||
                    "must be an integer";
            }

            for (name in checks) {
                count = options[name];
                if (v.isNumber(count) && !checks[name](value, count)) {
                    // This picks the default message if specified
                    // For example the greaterThan check uses the message from
                    // this.notGreaterThan so we capitalize the name and prepend "not"
                    var key = "not" + v.capitalize(name);
                    var msg = options[key] ||
                        this[key] ||
                        this.message ||
                        "must be %{type} %{count}";

                    errors.push(v.format(msg, {
                        count: count,
                        type: prettify(name)
                    }));
                }
            }

            if (options.odd && value % 2 !== 1) {
                errors.push(options.notOdd ||
                    this.notOdd ||
                    this.message ||
                    "must be odd");
            }
            if (options.even && value % 2 !== 0) {
                errors.push(options.notEven ||
                    this.notEven ||
                    this.message ||
                    "must be even");
            }

            if (errors.length) {
                return options.message || errors;
            }
        },
        datetime: v.extend(function(value, options) {
            if (!v.isFunction(this.parse) || !v.isFunction(this.format)) {
                throw new Error("Both the parse and format functions needs to be set to use the datetime/date validator");
            }

            // Empty values are fine
            if (!v.isDefined(value)) {
                return;
            }

            options = v.extend({}, this.options, options);

            var err, errors = [],
                earliest = options.earliest ? this.parse(options.earliest, options) : NaN,
                latest = options.latest ? this.parse(options.latest, options) : NaN;

            value = this.parse(value, options);

            // 86400000 is the number of milliseconds in a day, this is used to remove
            // the time from the date
            if (isNaN(value) || options.dateOnly && value % 86400000 !== 0) {
                err = options.notValid ||
                    options.message ||
                    this.notValid ||
                    "must be a valid date";
                return v.format(err, { value: arguments[0] });
            }

            if (!isNaN(earliest) && value < earliest) {
                err = options.tooEarly ||
                    options.message ||
                    this.tooEarly ||
                    "must be no earlier than %{date}";
                err = v.format(err, {
                    value: this.format(value, options),
                    date: this.format(earliest, options)
                });
                errors.push(err);
            }

            if (!isNaN(latest) && value > latest) {
                err = options.tooLate ||
                    options.message ||
                    this.tooLate ||
                    "must be no later than %{date}";
                err = v.format(err, {
                    date: this.format(latest, options),
                    value: this.format(value, options)
                });
                errors.push(err);
            }

            if (errors.length) {
                return v.unique(errors);
            }
        }, {
            parse: null,
            format: null
        }),
        date: function(value, options) {
            options = v.extend({}, options, { dateOnly: true });
            return v.validators.datetime.call(v.validators.datetime, value, options);
        },
        format: function(value, options) {
            if (v.isString(options) || (options instanceof RegExp)) {
                options = { pattern: options };
            }

            options = v.extend({}, this.options, options);

            var message = options.message || this.message || "is invalid",
                pattern = options.pattern,
                match;

            // Empty values are allowed
            if (!v.isDefined(value)) {
                return;
            }
            if (!v.isString(value)) {
                return message;
            }

            if (v.isString(pattern)) {
                pattern = new RegExp(options.pattern, options.flags);
            }
            match = pattern.exec(value);
            if (!match || match[0].length != value.length) {
                return message;
            }
        },
        inclusion: function(value, options) {
            // Empty values are fine
            if (!v.isDefined(value)) {
                return;
            }
            if (v.isArray(options)) {
                options = { within: options };
            }
            options = v.extend({}, this.options, options);
            if (v.contains(options.within, value)) {
                return;
            }
            var message = options.message ||
                this.message ||
                "^%{value} is not included in the list";
            return v.format(message, { value: value });
        },
        exclusion: function(value, options) {
            // Empty values are fine
            if (!v.isDefined(value)) {
                return;
            }
            if (v.isArray(options)) {
                options = { within: options };
            }
            options = v.extend({}, this.options, options);
            if (!v.contains(options.within, value)) {
                return;
            }
            var message = options.message || this.message || "^%{value} is restricted";
            return v.format(message, { value: value });
        },
        email: v.extend(function(value, options) {
            options = v.extend({}, this.options, options);
            var message = options.message || this.message || "is not a valid email";
            // Empty values are fine
            if (!v.isDefined(value)) {
                return;
            }
            if (!v.isString(value)) {
                return message;
            }
            if (!this.PATTERN.exec(value)) {
                return message;
            }
        }, {
            PATTERN: /^[a-z0-9\u007F-\uffff!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9\u007F-\uffff!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z]{2,}$/i
        }),
        equality: function(value, options, attribute, attributes, globalOptions) {
            if (!v.isDefined(value)) {
                return;
            }

            if (v.isString(options)) {
                options = { attribute: options };
            }
            options = v.extend({}, this.options, options);
            var message = options.message ||
                this.message ||
                "is not equal to %{attribute}";

            if (v.isEmpty(options.attribute) || !v.isString(options.attribute)) {
                throw new Error("The attribute must be a non empty string");
            }

            var otherValue = v.getDeepObjectValue(attributes, options.attribute),
                comparator = options.comparator || function(v1, v2) {
                    return v1 === v2;
                },
                prettify = options.prettify ||
                (globalOptions && globalOptions.prettify) ||
                v.prettify;

            if (!comparator(value, otherValue, options, attribute, attributes)) {
                return v.format(message, { attribute: prettify(options.attribute) });
            }
        },

        // A URL validator that is used to validate URLs with the ability to
        // restrict schemes and some domains.
        url: function(value, options) {
            if (!v.isDefined(value)) {
                return;
            }

            options = v.extend({}, this.options, options);

            var message = options.message || this.message || "is not a valid url",
                schemes = options.schemes || this.schemes || ['http', 'https'],
                allowLocal = options.allowLocal || this.allowLocal || false;

            if (!v.isString(value)) {
                return message;
            }

            // https://gist.github.com/dperini/729294
            var regex =
                "^" +
                // protocol identifier
                "(?:(?:" + schemes.join("|") + ")://)" +
                // user:pass authentication
                "(?:\\S+(?::\\S*)?@)?" +
                "(?:";

            var tld = "(?:\\.(?:[a-z\\u00a1-\\uffff]{2,}))";

            if (allowLocal) {
                tld += "?";
            } else {
                regex +=
                    // IP address exclusion
                    // private & local networks
                    "(?!(?:10|127)(?:\\.\\d{1,3}){3})" +
                    "(?!(?:169\\.254|192\\.168)(?:\\.\\d{1,3}){2})" +
                    "(?!172\\.(?:1[6-9]|2\\d|3[0-1])(?:\\.\\d{1,3}){2})";
            }

            regex +=
                // IP address dotted notation octets
                // excludes loopback network 0.0.0.0
                // excludes reserved space >= 224.0.0.0
                // excludes network & broacast addresses
                // (first & last IP address of each class)
                "(?:[1-9]\\d?|1\\d\\d|2[01]\\d|22[0-3])" +
                "(?:\\.(?:1?\\d{1,2}|2[0-4]\\d|25[0-5])){2}" +
                "(?:\\.(?:[1-9]\\d?|1\\d\\d|2[0-4]\\d|25[0-4]))" +
                "|" +
                // host name
                "(?:(?:[a-z\\u00a1-\\uffff0-9]-*)*[a-z\\u00a1-\\uffff0-9]+)" +
                // domain name
                "(?:\\.(?:[a-z\\u00a1-\\uffff0-9]-*)*[a-z\\u00a1-\\uffff0-9]+)*" +
                tld +
                ")" +
                // port number
                "(?::\\d{2,5})?" +
                // resource path
                "(?:[/?#]\\S*)?" +
                "$";

            var PATTERN = new RegExp(regex, 'i');
            if (!PATTERN.exec(value)) {
                return message;
            }
        }
    };

    validate.formatters = {
        detailed: function(errors) { return errors; },
        flat: v.flattenErrorsToArray,
        grouped: function(errors) {
            var attr;

            errors = v.groupErrorsByAttribute(errors);
            for (attr in errors) {
                errors[attr] = v.flattenErrorsToArray(errors[attr]);
            }
            return errors;
        },
        constraint: function(errors) {
            var attr;
            errors = v.groupErrorsByAttribute(errors);
            for (attr in errors) {
                errors[attr] = errors[attr].map(function(result) {
                    return result.validator;
                }).sort();
            }
            return errors;
        }
    };

    validate.exposeModule(validate, this, exports, module, define);
}).call(this,
    typeof exports !== 'undefined' ? /* istanbul ignore next */ exports : null,
    typeof module !== 'undefined' ? /* istanbul ignore next */ module : null,
    typeof define !== 'undefined' ? /* istanbul ignore next */ define : null);
/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var CURRENT_URL = window.location.href.split("#")[0].split("?")[0],
    $BODY = $("body"),
    $MENU_TOGGLE = $("#menu_toggle"),
    $SIDEBAR_MENU = $("#sidebar-menu"),
    $SIDEBAR_FOOTER = $(".sidebar-footer"),
    $LEFT_COL = $(".left_col"),
    $RIGHT_COL = $(".right_col"),
    $NAV_MENU = $(".nav_menu"),
    $FOOTER = $("footer");
    $NAV_TITLE = $(".nav_title")
var itemsList = null;

// Sidebar
$(document).ready(function() {
    // TODO: This is some kind of easy fix, maybe we can improve this
    var setContentHeight = function() {
        // reset height
        $RIGHT_COL.css("min-height", $(window).height());

        var bodyHeight = $BODY.outerHeight(),
            footerHeight = $BODY.hasClass("footer_fixed") ? -10 : $FOOTER.height(),
            leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
            contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

        // normalize content
        contentHeight -= $NAV_MENU.height() + footerHeight;

        $RIGHT_COL.css("min-height", contentHeight);
    };

    $SIDEBAR_MENU.find("a").on("click", function(ev) {
        var $li = $(this).parent();

        if ($li.is(".active")) {
            $li.removeClass("active active-sm");
            $("ul:first", $li).slideUp(function() {
                setContentHeight();
            });
        } else {
            // prevent closing menu if we are on child menu
            if (!$li.parent().is(".child_menu")) {
                $SIDEBAR_MENU.find("li").removeClass("active active-sm");
                $SIDEBAR_MENU.find("li ul").slideUp();
            }

            $li.addClass("active");

            $("ul:first", $li).slideDown(function() {
                setContentHeight();
            });
        }

        $.ajax({
            url: base_url + "users/setBodyClass",
            data: {"bodyClass": $BODY.attr('class')},
            success: function(response) {
            }
        });
    });

    $NAV_TITLE.find("a").on("click", function(){
        $.ajax({
            url: base_url + "users/setBodyClass",
            data: {"bodyClass": $BODY.attr('class')},
            success: function(response) {
            }
        });
    });

    // toggle small or large menu
    $MENU_TOGGLE.on("click", function() {
        if ($BODY.hasClass("nav-md")) {
            $SIDEBAR_MENU.find("li.active ul").hide();
            $SIDEBAR_MENU
                .find("li.active")
                .addClass("active-sm")
                .removeClass("active");
        } else {
            $SIDEBAR_MENU.find("li.active-sm ul").show();
            $SIDEBAR_MENU
                .find("li.active-sm")
                .addClass("active")
                .removeClass("active-sm");
        }

        $BODY.toggleClass("nav-md nav-sm");

        setContentHeight();

        $(".datatable").each(function() {
            $(this)
                .dataTable()
                .fnDraw();
        });
    });
    // check active menu
    $SIDEBAR_MENU
        .find('a[href="' + CURRENT_URL + '"]')
        .parent("li")
        .addClass("current-page");

    // $SIDEBAR_MENU
    //     .find("a")
    //     .filter(function() {
    //         return this.href == CURRENT_URL;
    //     })
    //     .parent("li")
    //     .addClass("current-page")
    //     .parents("ul")
    //     .slideDown(function() {
    //         setContentHeight();
    //     })
    //     .parent()
    //     .addClass("active");

    $SIDEBAR_MENU
        .find("a")
        .filter(function() {
            return this.href.indexOf(controllerName || ' ') >= 0;
        })
        .parent("li")
        .parents("ul")
        .slideDown(function() {
            setContentHeight();
        })
        .parent()
        .addClass("active");

    // recompute content when resizing
    // $(window).smartresize(function() {
    //     setContentHeight();
    // });

    setContentHeight();

    // fixed sidebar
    if ($.fn.mCustomScrollbar) {
        $(".menu_fixed").mCustomScrollbar({
            autoHideScrollbar: true,
            theme: "minimal",
            mouseWheel: {
                preventDefault: true
            }
        });
    }
});
// /Sidebar

// Panel toolbox
$(document).ready(function() {
    $(".collapse-link").on("click", function() {
        var $BOX_PANEL = $(this).closest(".x_panel"),
            $ICON = $(this).find("i"),
            $BOX_CONTENT = $BOX_PANEL.find(".x_content");

        // fix for some div with hardcoded fix class
        if ($BOX_PANEL.attr("style")) {
            $BOX_CONTENT.slideToggle(200, function() {
                $BOX_PANEL.removeAttr("style");
            });
        } else {
            $BOX_CONTENT.slideToggle(200);
            $BOX_PANEL.css("height", "auto");
        }

        $ICON.toggleClass("fa-chevron-up fa-chevron-down");
    });

    $(".close-link").click(function() {
        var $BOX_PANEL = $(this).closest(".x_panel");

        $BOX_PANEL.remove();
    });
});
// /Panel toolbox

// Tooltip
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip({
        container: "body"
    });
});
// /Tooltip

// Progressbar
$(document).ready(function() {
    if ($(".progress .progress-bar")[0]) {
        $(".progress .progress-bar").progressbar();
    }
});
// /Progressbar

// Switchery
$(document).ready(function() {
    if ($(".js-switch")[0]) {
        var elems = Array.prototype.slice.call(
            document.querySelectorAll(".js-switch")
        );
        elems.forEach(function(html) {
            var switchery = new Switchery(html, {
                color: "#26B99A"
            });
        });
    }
});
// /Switchery

// iCheck
$(document).ready(function() {
    if ($("input.flat")[0]) {
        $(document).ready(function() {
            $("input.flat").iCheck({
                checkboxClass: "icheckbox_flat-green",
                radioClass: "iradio_flat-green"
            });
        });
    }
});
// /iCheck

// Table
$("table input").on("ifChecked", function() {
    checkState = "";
    $(this)
        .parent()
        .parent()
        .parent()
        .addClass("selected");
    countChecked();
});
$("table input").on("ifUnchecked", function() {
    checkState = "";
    $(this)
        .parent()
        .parent()
        .parent()
        .removeClass("selected");
    countChecked();
});

var checkState = "";

$(".bulk_action input").on("ifChecked", function() {
    checkState = "";
    $(this)
        .parent()
        .parent()
        .parent()
        .addClass("selected");
    countChecked();
});
$(".bulk_action input").on("ifUnchecked", function() {
    checkState = "";
    $(this)
        .parent()
        .parent()
        .parent()
        .removeClass("selected");
    countChecked();
});
$(".bulk_action input#check-all").on("ifChecked", function() {
    checkState = "all";
    countChecked();
});
$(".bulk_action input#check-all").on("ifUnchecked", function() {
    checkState = "none";
    countChecked();
});

function countChecked() {
    if (checkState === "all") {
        $(".bulk_action input[name='table_records']").iCheck("check");
    }
    if (checkState === "none") {
        $(".bulk_action input[name='table_records']").iCheck("uncheck");
    }

    var checkCount = $(".bulk_action input[name='table_records']:checked").length;

    if (checkCount) {
        $(".column-title").hide();
        $(".bulk-actions").show();
        $(".action-cnt").html(checkCount + " Records Selected");
    } else {
        $(".column-title").show();
        $(".bulk-actions").hide();
    }
}

// Accordion
$(document).ready(function() {
    $(".expand").on("click", function() {
        $(this)
            .next()
            .slideToggle(200);
        $expand = $(this).find(">:first-child");

        if ($expand.text() == "+") {
            $expand.text("-");
        } else {
            $expand.text("+");
        }
    });
});

// NProgress
if (typeof NProgress != "undefined") {
    $(document).ready(function() {
        NProgress.start();
    });

    $(window).on("load", function() {
        NProgress.done();
    });
}
// Accordion
$(document).ready(function() {
    $(".datatable").each(function(idx, element) {
        var dataTB = $(element).dataTable({
            oLanguage: {
                sSearch: "Filter: "
            },
            "scrollX": true,
            // aaSorting: []
            ordering: false
        });
        if ($(element).attr('data-modal')) {

            $($(element).attr('data-modal')).on('shown.bs.modal', function() {
                dataTB.fnDraw();
            })

        }
    });
    $(".date:not([readonly])").datepicker({
        todayHighlight: true,
        format: "d M, yyyy",
        autoclose: true
    });
    // $('#items_orders_list').dataTable({
    //     "oLanguage": {
    //         "sSearch": "Filter: "
    //     },
    //     "bFilter": false
    // });
});
var theme = {
    color: [
        '#0f4f8e', '#1fb8c5', '#d47300', '#00d40e',
        '#ff0000', '#7366ea', '#9a613b', '#757575',
        '#0097ff', '#fff400'
    ],

    title: {
        itemGap: 8,
        textStyle: {
            fontWeight: 'normal',
            color: '#408829'
        }
    },

    dataRange: {
        color: ['#1f610a', '#97b58d']
    },

    toolbox: {
        color: ['#408829', '#408829', '#408829', '#408829']
    },

    tooltip: {
        backgroundColor: 'rgba(0,0,0,0.5)',
        axisPointer: {
            type: 'line',
            lineStyle: {
                color: '#408829',
                type: 'dashed'
            },
            crossStyle: {
                color: '#408829'
            },
            shadowStyle: {
                color: 'rgba(200,200,200,0.3)'
            }
        }
    },

    dataZoom: {
        dataBackgroundColor: '#eee',
        fillerColor: 'rgba(64,136,41,0.2)',
        handleColor: '#408829'
    },
    grid: {
        borderWidth: 0
    },

    categoryAxis: {
        axisLine: {
            lineStyle: {
                color: '#408829'
            }
        },
        splitLine: {
            lineStyle: {
                color: ['#eee']
            }
        }
    },

    valueAxis: {
        axisLine: {
            lineStyle: {
                color: '#408829'
            }
        },
        splitArea: {
            show: true,
            areaStyle: {
                color: ['rgba(250,250,250,0.1)', 'rgba(200,200,200,0.1)']
            }
        },
        splitLine: {
            lineStyle: {
                color: ['#eee']
            }
        }
    },
    timeline: {
        lineStyle: {
            color: '#408829'
        },
        controlStyle: {
            normal: { color: '#408829' },
            emphasis: { color: '#408829' }
        }
    },

    k: {
        itemStyle: {
            normal: {
                color: '#68a54a',
                color0: '#a9cba2',
                lineStyle: {
                    width: 1,
                    color: '#408829',
                    color0: '#86b379'
                }
            }
        }
    },
    map: {
        itemStyle: {
            normal: {
                areaStyle: {
                    color: '#ddd'
                },
                label: {
                    textStyle: {
                        color: '#c12e34'
                    }
                }
            },
            emphasis: {
                areaStyle: {
                    color: '#99d2dd'
                },
                label: {
                    textStyle: {
                        color: '#c12e34'
                    }
                }
            }
        }
    },
    force: {
        itemStyle: {
            normal: {
                linkStyle: {
                    strokeColor: '#408829'
                }
            }
        }
    },
    chord: {
        padding: 4,
        itemStyle: {
            normal: {
                lineStyle: {
                    width: 1,
                    color: 'rgba(128, 128, 128, 0.5)'
                },
                chordStyle: {
                    lineStyle: {
                        width: 1,
                        color: 'rgba(128, 128, 128, 0.5)'
                    }
                }
            },
            emphasis: {
                lineStyle: {
                    width: 1,
                    color: 'rgba(128, 128, 128, 0.5)'
                },
                chordStyle: {
                    lineStyle: {
                        width: 1,
                        color: 'rgba(128, 128, 128, 0.5)'
                    }
                }
            }
        }
    },
    gauge: {
        startAngle: 225,
        endAngle: -45,
        axisLine: {
            show: true,
            lineStyle: {
                color: [
                    [0.2, '#86b379'],
                    [0.8, '#68a54a'],
                    [1, '#408829']
                ],
                width: 8
            }
        },
        axisTick: {
            splitNumber: 10,
            length: 12,
            lineStyle: {
                color: 'auto'
            }
        },
        axisLabel: {
            textStyle: {
                color: 'auto'
            }
        },
        splitLine: {
            length: 18,
            lineStyle: {
                color: 'auto'
            }
        },
        pointer: {
            length: '90%',
            color: 'auto'
        },
        title: {
            textStyle: {
                color: '#333'
            }
        },
        detail: {
            textStyle: {
                color: 'auto'
            }
        }
    },
    textStyle: {
        fontFamily: 'Arial, Verdana, sans-serif'
    }
};
if ($('#echart_pie').length) {

    var echartPie = echarts.init(document.getElementById('echart_pie'), theme);

    echartPie.setOption({
        title: {
            // text: 'Sales Composition',
            text: lang['sales_composition'],
            x: 'left'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            type: 'scroll',
            orient: 'vertical',
            right: 10,
            top: 20,
            bottom: 20,
            data: ['Salesman1', 'Salesman2', 'Salesman3', 'Salesman4', 'Salesman5', 'Salesman6', 'Salesman7', 'Salesman8', 'Salesman9', 'Others']
        },
        toolbox: {
            show: true,
            feature: {
                magicType: {
                    show: true,
                    type: ['pie', 'funnel'],
                    option: {
                        funnel: {
                            x: '25%',
                            width: '50%',
                            funnelAlign: 'left',
                            max: 1548
                        }
                    }
                },
                restore: {
                    show: false,
                    title: "Restore"
                },
                saveAsImage: {
                    show: false,
                    title: "Save Image"
                }
            }
        },
        calculable: true,
        series: [{
            name: 'Sales Composition',
            type: 'pie',
            radius: '50%',
            center: ['38%', '50%'],
            data: [{
                value: 335,
                name: 'Salesman1'
            }, {
                value: 310,
                name: 'Salesman2'
            }, {
                value: 234,
                name: 'Salesman3'
            }, {
                value: 135,
                name: 'Salesman4'
            }, {
                value: 1548,
                name: 'Salesman5'
            }, {
                value: 711,
                name: 'Salesman6'
            }, {
                value: 844,
                name: 'Salesman7'
            }, {
                value: 148,
                name: 'Salesman8'
            }, {
                value: 1148,
                name: 'Salesman9'
            }, {
                value: 674,
                name: 'Others'
            }]
        }]
    });

    var dataStyle = {
        normal: {
            label: {
                show: false
            },
            labelLine: {
                show: false
            }
        }
    };

    var placeHolderStyle = {
        normal: {
            color: 'rgba(0,0,0,0)',
            label: {
                show: false
            },
            labelLine: {
                show: false
            }
        },
        emphasis: {
            color: 'rgba(0,0,0,0)'
        }
    };

}

// Bar chart

if ($("#mybarChart").length) {
    var ctx = document.getElementById("mybarChart");
    var mybarChart = new Chart(ctx, {
        type: "bar",
        data: {
            // labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            labels: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
            datasets: [{
                label: "# Sales",
                backgroundColor: "#26B99A",
                data: [51, 30, 40, 28, 92, 50, 45, 21, 35, 46, 39, 69]
            }]
        },

        options: {
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}
// enable edit content
var historyClick = [];
// enable edit content
var historyValue = [];

// function updateItem(line, objButton) {
//     var idx = historyClick.indexOf(line);
//     var ele = $(
//         "#items_orders_list tbody tr:nth-child(" + line + ") td:nth-child(4) div"
//     );
//     var quantity = parseInt($(ele).text());
//     if (idx !== -1) {
//         var differVal = quantity - historyValue[idx];
//         var itemId = $(
//             "#items_orders_list tbody tr:nth-child(" + line + ") td:nth-child(2)"
//         ).text();
//         console.log(differVal, itemId);
//         updateItemquantity(itemId, differVal);
//         $(ele)
//             .attr("contenteditable", false)
//             .parent()
//             .removeClass("content-edit");
//         $("#add").removeAttr("disabled");
//         $(objButton).html('<span class="glyphicon glyphicon-edit"></span>');
//         historyClick.splice(idx, 1);
//         historyValue.splice(idx, 1);
//         return;
//     }
//     $(ele)
//         .attr("contenteditable", true)
//         .parent()
//         .addClass("content-edit");
//     $(objButton).html('<span class="glyphicon glyphicon-check"></span>');
//     placeCaretAtEnd($(ele).get(0));
//     $(ele).attr("onkeypress", "return isNumberKey(event)");
//     $(ele).attr("oninput", "return maxLengthDivCheck(this)");
//     $("#add").attr("disabled", "disabled");
//     historyClick.push(line);
//     historyValue.push(quantity);
// }
//focus to last character
function placeCaretAtEnd(el) {
    el.focus();
    if (
        typeof window.getSelection != "undefined" &&
        typeof document.createRange != "undefined"
    ) {
        var range = document.createRange();
        range.selectNodeContents(el);
        range.collapse(false);
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    } else if (typeof document.body.createTextRange != "undefined") {
        var textRange = document.body.createTextRange();
        textRange.moveToElementText(el);
        textRange.collapse(false);
        textRange.select();
    }
}
//Check maxlength for  number
function maxLengthDivCheck(object) {
    var max = $(object).attr("maxLength");
    max = max != undefined && max > 0 ? max : 6;
    if ($(object).text().length > max)
        $(object).text(
            $(object)
            .text()
            .slice(0, max)
        );
    placeCaretAtEnd($(object).get(0));
}
//Check maxlength for input number
function maxLengthCheck(object) {
    if (object.value.length > object.maxLength)
        object.value = object.value.slice(0, object.maxLength);
}

// validate input type number
function isNumberKey(evt) {
    var charCode = evt.which ? evt.which : event.keyCode;
    return !(charCode > 31 && (charCode < 48 || charCode > 57));
}
//config choose file
$("#choose_file").on("click", function() {
    $("#file_upload_hidden").trigger("click");
});
$("input[type=file]#file_upload_hidden").change(function() {
    var pathFile = this.value.replace(/^.*[\\\/]/, '');
    $("#file_upload").val(pathFile);
    PreviewPdf();
});
//preview filepdf
function PreviewPdf() {
    pdffile = document.getElementById("file_upload_hidden").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    $('#pre_file_upload').attr('src', pdffile_url);
}
// send post request function
function sendPostRequest(url, bodyData) {
    console.log(url);
    var res = null;
    $.ajax({
        url: url,
        type: "POST",
        data: bodyData,
        async: false,
        success: function(response) {
            console.log(response);
            res = response;
        },
        error: function(error) {
            console.log(error);
        }
    });
    return res;
}
// send get request function
function sendGetRequest(url, bodyData) {
    console.log(url);
    $.ajax({
        url: url,
        type: "GET",
        data: bodyData,
        success: function(response) {
            console.log(response);
        },
        error: function(xhr) {
            console.log(xhr);
        }
    });
}

// search object in array
function searchItems(nameKey, myArray) {
    for (var i = 0; i < myArray.length; i++) {
        if (myArray[i].item_no === nameKey) {
            return myArray[i];
        }
    }
}

$(document).ready(function() {
    $("#order").focus();
});
$('.has-clear input[type="text"]:not([readonly])').on('input propertychange change', function() {
    var $this = $(this);
    var visible = Boolean($this.val());
    $this.siblings('.form-control-clear').toggleClass('hidden', !visible);
}).trigger('propertychange');

$(document).on("click", '.form-control-clear', function() {
    $(this).siblings('input[type="text"]').val('')
        .trigger('propertychange').focus();
});

function btn_add_delivery() {
    var count = $('div.group-delivery:last').attr('id');
    count = Number(count);
    var count_group_delivery = $('.group-delivery').length + 1;
    delivery_number = count + 1;
    // alert(delivery_number);
    // return;
    // alert('asd');
    var html = "<div class='form-group'>" +
        "<div class='col-sm-12 no-padding-left'>" +
        "<label class='control-label col-sm-2 form-title' for='delivery_to' ></label>" +
        "<div class='col-sm-2'>" +
        "<label class='control-label form-title' for='delivery' style='margin-left: -3px;'>Delivery " + count_group_delivery + "</label>" +
        "</div>" +
        "<div class='col-sm-8 no-padding-right'>" +
        "<select class='form-control' id='delivery_to' name='delivery_to'>" +
        "<option value=''></option>" +
        "</select>" +
        "<button type='button' onclick='delete_delivery(this)' class='btn btn-delete-delivery ' id='delivery" + count + "'><i class='fa fa-trash'></i></button>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "<div class='form-group'>" +
        "<div class='col-sm-12 no-padding-left'>" +
        "<label class='control-label col-sm-2 form-title' for='delivery_to' ></label>" +
        "<div class='col-sm-2'>" +
        "<label class='control-label form-title' for='delivery' style='margin-left: -3px;'>Delivery Address</label>" +
        "</div>" +
        "<div class='col-sm-8 no-padding-right'>" +
        "<textarea class='form-control form-rounded' rows='2' id='delivery_address' name='delivery_address' ></textarea>" +
        "</div>" +
        "</div>" +
        "</div>";
    var group_delivery_new = "<div id='" + delivery_number + "' class='group-delivery'" +
        "</div>";
    $("#" + count + "").append(html);
    $("#" + count + "").after(group_delivery_new);
}

function delete_delivery(event) {
    var count = $(event).attr('id');
    var res = count.substring(8, count.length);
    $("#" + res + "").remove();
}

// First, checks if it isn't implemented yet.
// Example: "{0} is dead, but {1} is alive! {0} {2}".format("ASP", "ASP.NET")
if (!String.prototype.format) {
    String.prototype.format = function() {
        var args = arguments;
        return this.replace(/{(\d+)}/g, function(match, number) {
            return typeof args[number] != 'undefined' ?
                args[number] :
                match;
        });
    };
}
if (!String.prototype.escape) {
    String.prototype.escape = function() {
        var map = {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            "\"": "&quot;",
            "'": "&#39;" // ' -> &apos; for XML only
        };
        return this.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
}
if (!String.prototype.hashCode) {
    String.prototype.hashCode = function() {
        // var hash = 0, i, chr;
        // if (this.length === 0) return hash;
        // for (i = 0; i < this.length; i++) {
        //   chr   = this.charCodeAt(i);
        //   hash  = ((hash << 5) - hash) + chr;
        //   hash |= 0; // Convert to 32bit integer
        // }
        hash = this.replace(/[ /]/g,'-');
        return hash;
      };
}

function snackbarShow(message, timeshow) {
    if (!message) return;
    if (!timeshow || isNaN(timeshow)) timeshow = 3000;

    $('#snackbar').html(message).addClass("show-message");
    clearTimeout(snackbarTimeout);
    var snackbarTimeout = setTimeout(function() {
        $('#snackbar').removeClass("show-message");
    }, timeshow);
}

function numberWithCommas(nStr) {
    // if (!$.isNumeric(x)) return 0;
    // x = parseFloat(x);
    // return x.toString().replace(/\B(?=(\d{3}.)+(?!\d))/g, ",");
    nStr = parseFloat(nStr);
    if(!nStr) return 0;
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
    x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
// Returns a string representing a number in fixed-point notation via currency.
Number.prototype.myToFixed = function(currency) {
    var fractionDigits = 4;
    var outputCurrency = currency.trim();
    if (outputCurrency == 'JPY') {
        fractionDigits = 2;
    }
    var x = Number(this||0);
    var num = x.toString().split(".");
    if(num[1]){
        var v = num[1];
        if (outputCurrency == 'VND') {
            var result = Number(num[0]);
            if(Number(v.substr(0, (1))) >= 5){
                return (++result);
            } else {
                return result;
            }
        }
        if (v.length > fractionDigits){
            v = v.substr(0, (fractionDigits));
            return (num[0]+'.'+v);
        } else {
            x = x.toFixed(fractionDigits);
        }
    } else {
        if(outputCurrency == 'VND') { 
            return x;
        } else {
            x = x.toFixed(fractionDigits);
        }
    }
    return x;
};

function formatDate(date) {
    if(date == null || date == ''){
        return '';
    }
    var date = new Date(date);
    var monthNames = [
        "Jan", "Feb", "Mar",
        "Apr", "May", "Jun", "Jul",
        "Aug", "Sep", "Oct",
        "Nov", "Dec"
    ];

    var day = date.getDate();
    var monthIndex = date.getMonth();
    var year = date.getFullYear();

    return day + ' ' + monthNames[monthIndex] + ', ' + year;
}

function checkQuantity(e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
        // Allow: Ctrl+A, Command+A
        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
        // Allow: home, end, left, right, down, up
        (e.keyCode >= 35 && e.keyCode <= 40)) {
        // let it happen, don't do anything
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
}
$('.text-uppercase').keyup(function() {
    this.value = this.value.toUpperCase();
});
// $(".has-clear :input:not([readonly])").append('<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>');

function moveCursorToEnd(input) {
    var originalValue = input.val();
    input.val('');
    input.blur().focus().val(originalValue);
}
// Polyfill Object.assign
if (typeof Object.assign != 'function') {
    // Must be writable: true, enumerable: false, configurable: true
    Object.defineProperty(Object, "assign", {
      value: function assign(target, varArgs) { // .length of function is 2
        'use strict';
        if (target == null) { // TypeError if undefined or null
          throw new TypeError('Cannot  convert undefined or null to object');
        }
  
        var to = Object(target);
  
        for (var index = 1; index < arguments.length; index++) {
          var nextSource = arguments[index];
  
          if (nextSource != null) { // Skip over if undefined or null
            for (var nextKey in nextSource) {
              // Avoid bugs when hasOwnProperty is shadowed
              if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                to[nextKey] = nextSource[nextKey];
              }
            }
          }
        }
        return to;
      },
      writable: true,
      configurable: true
    });
  }

// Override parseFloat function because handle NaN result of parseFloat
var origParseFloat = parseFloat;
parseFloat = function(str) {
    return origParseFloat(str)||0;
}
/*
 * Chained - jQuery / Zepto chained selects plugin
 *
 * Copyright (c) 2010-2017 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/projects/chained
 *
 * Version: 2.0.0-beta.3
 *
 */

;(function($, window, document, undefined) {
    "use strict";

    $.fn.remoteChained = function(options) {

        var settings = $.extend({}, $.fn.remoteChained.defaults, options);

        /* Loading text always clears the select. */
        if (settings.loading) {
            settings.clear = true;
        }

        return this.each(function() {

            /* Save this to self because this changes when scope changes. */
            var self = this;
            var request = false; /* Track xhr requests. */

            $(settings.parents).each(function() {
                $(this).bind("change", function() {

                    /* Build data array from parents values. */
                    var data = {};
                    $(settings.parents).each(function() {
                        var id = $(this).attr(settings.attribute);
                        var value = ($(this).is("select") ? $(":selected", this) : $(this)).val();
                        data[id] = value;

                        /* Optionally also depend on values from these inputs. */
                        if (settings.depends) {
                            $(settings.depends).each(function() {
                                /* Do not include own value. */
                                if (self !== this) {
                                    var id = $(this).attr(settings.attribute);
                                    var value = $(this).val();
                                    data[id] = value;
                                }
                            });
                        }
                    });

                    /* If previous request running, abort it. */
                    /* TODO: Probably should use Sinon to test this. */
                    if (request && $.isFunction(request.abort)) {
                        request.abort();
                        request = false;
                    }

                    if (settings.clear) {
                        if (settings.loading) {
                            /* Clear the select and show loading text. */
                            build.call(self, {"" : settings.loading});
                        } else {
                            /* Clear the select. */
                            $("option", self).remove();
                        }

                        /* Force updating the children to clear too. */
                        $(self).trigger("change");
                    }

                    request = $.getJSON(settings.url, data, function(json) {
                        json = settings.data(json);
                        build.call(self, json);
                        /* Force updating the children. */
                        $(self).trigger("change");
                    });
                });

                /* If we have bootstrapped data given in options. */
                if (settings.bootstrap) {
                    build.call(self, settings.bootstrap);
                    settings.bootstrap = null;
                }
            });

            /* Build the select from given data. */
            function build(json) {
                /* If select already had something selected, preserve it. */
                var selectedKey = $(":selected", self).val();

                /* Clear the select. */
                $("option", self).remove();

                if ($.isArray(json)) {
                    /* Add new options from json which is an array of objects. */
                    /* [ {"":"--"},{"series-1":"1 series"},{"series-3"}:{"3 series"}] */
                    $.each(json, function(key, value) {
                        $.each(value, function(key, value) {
                            if ("selected" === key) {
                                selectedKey = value;
                            } else {
                                var option = $("<option />").val(key).append(value);
                                $(self).append(option);
                            }
                        });
                    });
                } else {
                    /* Add new options from json which is an object. */
                    /* {"":"--","series-1":"1 series","series-3":"3 series"} */
                    $.each(json, function(key, value) {
                        if (json.hasOwnProperty(key)) {
                            /* Set the selected option from JSON. */
                            if ("selected" === key) {
                                selectedKey = value;
                            } else {
                                var option = $("<option />").val(key).append(value);
                                $(self).append(option);
                            }
                        }
                    });
                }

                /* Loop option again to set selected. IE needed this... */
                $(self).children().each(function() {
                    if ($(this).val() === selectedKey + "") {
                        $(this).attr("selected", "selected");
                    }
                });

                /* If we have only the default value disable select. */
                if (1 === $("option", self).length && $(self).val() === "") {
                    $(self).prop("disabled", true);
                } else {
                    $(self).prop("disabled", false);
                }
            }
        });
    };

    /* Alias for those who like to use more English like syntax. */
    $.fn.remoteChainedTo = $.fn.remoteChained;

    /* Default settings for plugin. */
    $.fn.remoteChained.defaults = {
        attribute: "name",
        depends: null,
        bootstrap: null,
        loading: null,
        clear: false,
        data: function(json) { return json; }
    };

})(window.jQuery || window.Zepto, window, document);
