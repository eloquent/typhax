# Typhax

*A flexible PHP type hinting syntax.*

## What is Typhax?

Typhax is a standard for the specification of PHP types in parameter type hints,
and anywhere a type needs to be described in a human-readable form.

It expands of the capabilities of [PHPDoc](http://en.wikipedia.org/wiki/PHPDoc)'s
@param tag type specifications to describe the type in more detail.

In addition to support for scalar types and class type hints supported by
PHPDoc, Typhax allows, amongst other things, the specification of key and value
types of traversable parameters such as arrays and iterators.

## Supported types

### Array

    array
    array<keyType, valueType>

An [array](http://php.net/array). The key type and value type can optionally
be specified. See the section on [traversable types](#traversable-types) below.

### Boolean

    boolean

A [boolean](http://php.net/boolean) true or false value. The value must be an
actual boolean, and not an equivalent integer.

### Callable

    callable

A user-defined callback function. This is exactly equivalent to the
[callable](http://php.net/manual/en/language.types.callable.php) type hint
introduced in PHP 5.4.

A callable can be any of the following:

 * A string containing the name of a function or static class method.
 * An array with an object at index 0 and the method name at index 1.
 * An array with a class name at index 0 and the method name at index 1.
 * A [closure](http://php.net/manual/en/functions.anonymous.php).
 * The result of a [create_function()](http://php.net/create_function) call.

### Float

    float

A [floating-point number](http://php.net/float). The value must be a true
floating point number, and not an equivalent string or integer.

### Integer

    integer

An [integer](http://php.net/integer). The value must be a true integer, and not
an equivalent string, boolean, or any other kind of value.

### Mixed

    mixed

The mixed type accepts any value of any type.

### Null

    null

A [null](http://php.net/manual/en/language.types.null.php).

### Object

    object
    ClassName
    ClassName<keyType, valueType>

The first form, `object`, indicates a value that is an
[object](http://www.php.net/manual/en/language.oop5.php) of *any* class.

The second form, `ClassName`, indicates a value that is an
[object](http://www.php.net/manual/en/language.oop5.php) of class `ClassName`.
This includes instances of `ClassName`, instances of objects whose class extends
from `ClassName`, and instances of objects that implement the `ClassName`
interface.

The third form, `ClassName<keyType, valueType>`, follows the same rules as the
second form, with the addition that the object must implement the
[Traversable](http://php.net/traversable) interface. The key type and value type
can optionally be specified. See the section on
[traversable types](#traversable-types) below.

#### Regarding namespaces

Namespace resolution should follow the same rules as PHP source code. That is;
if the class name is in the current namespace, or has a relevant use statement,
it's okay to use the short version.

In cases where the class name would not resolve correctly in, for example, an
*instanceof* condition, the canonical (full) version of the class name must be
used.

If you need to resolve these at runtime, you can use the
[Cosmos component](https://github.com/eloquent/cosmos).

### Resource

    resource
    resource {ofType: resourceType}

The first form, `resource`, indicates a value that is a
[resource](http://php.net/resource) of *any* type.

The second form, `resource {ofType: resourceType}`, indicates a value that is a
[resource](http://php.net/resource) that returns a string equal to
'resourceType' when passed to [get_resource_type()](http://php.net/get_resource_type).

### Stream

    stream
    stream {readable: true, writable: true}
    stream {readable: true, writable: false}
    stream {readable: false, writable: true}

Represents a [stream](http://php.net/stream) resource. The **readable** and
**writable** attributes determine the requirements for the **mode** of the
stream.

See the PHP documentation for [fopen()](http://php.net/fopen) for more
information about stream modes.

### String

    string

A [string](http://php.net/string). The value must be a true string, and not
any other type that can be converted to a string.

### Stringable

    stringable

Represents a value of any type that can be silently converted to a string. This
includes strings, integers, floats, and objects that have a `__toString()`
method.

Arrays, booleans, nulls, resources, and objects without a `__toString()` method
do not qualify as 'stringable'.

### Tuple

    tuple<typeA, typeB, typeC, ...>

A [tuple](http://en.wikipedia.org/wiki/Tuple) is an array value of a fixed size,
with each element being of a specific type.

Tuples are generally referred to as an *n*-tuple, where *n* is the number of
elements.

For example, a 2-tuple definition where the first element is a string, and the
second element is an integer, looks like `tuple<string, integer>`.

Tuple arrays must be sequential. That is, the keys of the array must be
integers, with the first key being 0, and subsequent keys incrementing by 1 for
each element of the tuple.

Tuples must have at least 1 element.

### Legacy types

The following types are also implemented, but are considered deprecated. They
exist primarily for compatibility with types and pseudo-types used in the PHP
manual, and an effort should be made to avoid their use:

 * `bool` = `boolean`
 * `callback` = `callable`
 * `double` = `float`
 * `int` = `integer`
 * `long` = `integer`
 * `number` = `integer|float`
 * `numeric` = equivalent to [is_numeric()](http://php.net/is_numeric)
 * `real` = `float`
 * `scalar` = `integer|float|string|boolean`

## Traversable types

Typhax supports the specification of key and value types for arrays and
[Traversable](http://php.net/traversable) objects.

The specification for key and value types is as follows:

    primaryType<keyType, valueType>

This specification represents a value of type `primaryType` which, when iterated
over, produces keys of type `keyType` and values of type `valueType`.

## Boolean type logic

Typhax supports boolean logic in type specifications. There are two operators,
the pipe symbol (|) which represents boolean OR, and the ampersand (&) which
represents boolean AND.

This specification:

    typeA|typeB

Represents a type that is either of type `typeA` OR of type `typeB`. A
real-world example might be `integer|float` to accept either an integer OR float
number.

This specification:

    typeA&typeB

Represents a type that is both of type `typeA` AND of type `typeB`. A
real-world example might be `InterfaceA|InterfaceB` to accept either only an
object that implements both `InterfaceA` AND `InterfaceB`.

## White space

In general, Typhax does not care whether white space is used in type
specifications. However, the above documentation should serve as a recommended
style guide.

## Code quality

Typhax strives to attain a high level of quality. A full test suite is
available, and code coverage is closely monitored.

### Latest revision test suite results
[![Build Status](https://secure.travis-ci.org/eloquent/typhax.png)](http://travis-ci.org/eloquent/typhax)

### Latest revision test suite coverage
<http://ci.ezzatron.com/report/typhax/coverage/>
