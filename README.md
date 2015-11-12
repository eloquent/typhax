# Typhax

*A flexible PHP type hinting syntax.*

[![Current version image][version-image]][current version]
[![Current build status image][build-image]][current build status]
[![Current coverage status image][coverage-image]][current coverage status]

[build-image]: http://img.shields.io/travis/eloquent/typhax/develop.svg?style=flat-square "Current build status for the develop branch"
[coverage-image]: https://img.shields.io/codecov/c/github/eloquent/typhax/develop.svg?style=flat-square "Current test coverage for the develop branch"
[current build status]: https://travis-ci.org/eloquent/typhax
[current coverage status]: https://codecov.io/github/eloquent/typhax
[current version]: https://packagist.org/packages/eloquent/typhax
[version-image]: https://img.shields.io/packagist/v/eloquent/typhax.svg?style=flat-square "This project uses semantic versioning"

## Installation and documentation

- Available as [Composer] package [eloquent/typhax].
- [API documentation] available.

[api documentation]: http://lqnt.co/typhax/artifacts/documentation/api/
[composer]: http://getcomposer.org/
[eloquent/typhax]: https://packagist.org/packages/eloquent/typhax

## What is Typhax?

Typhax is a standard for the specification of PHP types in parameter type hints,
and anywhere a type needs to be described in a human-readable form.

It expands on the capabilities of existing conventions for specifying type
requirements, such as those used in the [PHP documentation] and PHPDoc's
[@param tag].

In addition to scalar and class type hints, Typhax allows powerful features,
including the specification of key and value types for arrays, as well as
compound types that use boolean logic (e.g. integer|float).

[@param tag]: http://www.phpdoc.org/docs/latest/references/phpdoc/tags/param.html
[php documentation]: http://php.net/manual/en/language.types.php

## Supported types

### Array

    array
    array<keyType,valueType>

An [array]. The key type and value type can optionally be specified. See the
section on [traversable types] below.

Equivalent to the [is_array()] function.

[is_array()]: http://php.net/is_array

### Boolean

    boolean

A [boolean] true or false value. The value must be an actual boolean, and not an
equivalent integer.

Equivalent to the [is_bool()] function.

[boolean]: http://php.net/boolean
[is_bool()]: http://php.net/is_bool

### Callable

    callable

A user-defined callback function. This is exactly equivalent to the [callable]
type hint introduced in PHP 5.4.

A callable can be any of the following:

- A string containing the name of a function or static class method.
- An array with an object at index 0 and the method name at index 1.
- An array with a class name at index 0 and the method name at index 1.
- A [closure].
- An object with an [__invoke()] method.
- The result of a [create_function()] call.

Equivalent to the [is_callable()] function.

[__invoke()]: http://php.net/manual/en/language.oop5.magic.php#object.invoke
[callable]: http://php.net/manual/en/language.types.callable.php
[closure]: http://php.net/manual/en/functions.anonymous.php
[create_function()]: http://php.net/create_function
[is_callable()]: http://php.net/is_callable

### Float

    float

A [floating-point number]. The value must be a true floating point number, and
not an equivalent string or integer.

Equivalent to the [is_float()] function.

[floating-point number]: http://php.net/float
[is_float()]: http://php.net/is_float

### Integer

    integer

An [integer]. The value must be a true integer, and not an equivalent string,
boolean, or any other kind of value.

Equivalent to the [is_int()] function.

[integer]: http://php.net/integer
[is_int()]: http://php.net/is_int

### Mixed

    mixed
    mixed<keyType,valueType>

The mixed type accepts any value of any type (including null).

Mixed can be treated as a [traversable type][traversable types], as in the
second example above. When used in this fashion, mixed indicates any type that
can be traversed, such as an [array] or an instance of the [Traversable]
interface. This is useful in the case that the value must be a collection, but
the outer type is not important.

### Null

    null

A [null] value.

Equivalent to `=== null`.

[null]: http://php.net/manual/en/language.types.null.php

### Object

    object
    ClassName
    ClassName<keyType,valueType>

The first form, `object`, indicates a value that is an [object] of *any* class.
This is equivalent to the [is_object()] function.

The second form, `ClassName`, indicates a value that is an [object] of class
`ClassName`. This includes instances of `ClassName`, instances of objects whose
class extends from `ClassName`, and instances of objects that implement the
`ClassName` interface. This is equivalent to the [instanceof] operator.

The third form, `ClassName<keyType,valueType>`, follows the same rules as the
second form, with the additional requirement that the object must implement the
[Traversable] interface. The key type and value type can optionally be
specified. See the section on [traversable types] below.

[instanceof]: http://php.net/manual/en/language.operators.type.php
[is_object()]: http://php.net/is_object

#### Regarding namespaces

Namespace resolution should follow the same rules as PHP source code. That is;
if the class name is in the current namespace, or has a relevant use statement,
it's okay to use the short version.

[Cosmos] can be used to aid in resolving class names at runtime.

[cosmos]: https://github.com/eloquent/cosmos

### Resource

    resource
    resource{ofType:resourceType}

The first form, `resource`, indicates a value that is a [resource] of *any*
type. This is equivalent to the [is_resource()] function.

The second form, `resource{ofType:resourceType}`, indicates a value that is a
[resource] that returns a string equal to 'resourceType' when passed to
[get_resource_type()].

[get_resource_type()]: http://php.net/get_resource_type
[is_resource()]: http://php.net/is_resource

### Stream

    stream
    stream{readable:true,writable:true}
    stream{readable:true,writable:false}
    stream{readable:false,writable:true}

Represents a [stream] resource. The **readable** and **writable** attributes
determine the requirements for the **mode** of the stream.

The stream type is equvalent to the Typhax type `resource{ofType:stream}`.

See the PHP documentation for [fopen()] for more information about stream modes.

[fopen()]: http://php.net/fopen
[stream]: http://php.net/stream

### String

    string

A [string]. The value must be a true string, and not any other type that can be
converted to a string.

Equivalent to the [is_string()] function.

[is_string()]: http://php.net/is_string
[string]: http://php.net/string

### Stringable

    stringable

Represents a value of any type that can be converted to a *useful* string
representation. This includes strings, integers, floats, and objects that have a
`__toString()` method.

Arrays, booleans, nulls, resources, and objects *without* a `__toString()`
method do not qualify as 'stringable'.

### Tuple

    tuple<typeA,typeB,typeC,...>

A [tuple] is an array value of a fixed size, with each element being of a
specific type.

Tuples are generally referred to as an *n*-tuple, where *n* is the number of
elements. For example, a 2-tuple definition where the first element is a string,
and the second element is an integer, looks like `tuple<string,integer>`.

Tuple arrays must be sequential. That is, the keys of the array must be
integers, with the first key being 0, and subsequent keys incrementing by 1 for
each element of the tuple.

As an example, `['foo', 1]` satisfies the constraint
`tuple<string,integer>`.

[tuple]: http://en.wikipedia.org/wiki/Tuple

### Legacy types

The following types are also implemented, but are considered deprecated. They
exist primarily for compatibility with types and pseudo-types used in the PHP
manual, and an effort should be made to avoid their use:

- `bool` = `boolean`
- `callback` = `callable`
- `double` = `float`
- `int` = `integer`
- `long` = `integer`
- `number` = `integer|float`
- `numeric` = equivalent to [is_numeric()]
- `real` = `float`
- `scalar` = `integer|float|string|boolean`

[is_numeric()]: http://php.net/is_numeric

## Traversable types

Typhax supports the specification of key and value types for arrays and
[Traversable] objects.

The specification for key and value types is as follows:

    primaryType<keyType,valueType>

This specification represents a value of type `primaryType` which, when iterated
over, produces keys of type `keyType` and values of type `valueType`.

### Omitting a traversable type's key type

The `keyType` may be omitted:

    primaryType<valueType>

This specification represents a value of type `primaryType` which, when iterated
over, produces **sequential integer keys starting with 0**, and values of type
`valueType`.

### Using `mixed` as the primary type in a traversable type

A traversable type may specify `mixed` as the primary type:

    mixed<keyType,valueType>

This specification represents a value of *any* type which can be iterated over,
producing keys of type `keyType` and values of type `valueType`.

## Boolean type logic

Typhax supports boolean logic in type specifications. There are two operators,
the pipe symbol (|) which represents boolean OR, and the plus symbol (+) which
represents boolean AND.

This specification:

    typeA|typeB

represents a type that is either of type `typeA` OR of type `typeB`. A
real-world example might be `integer|float` to accept either an integer OR float
number.

This specification:

    typeA+typeB

represents a type that is both of type `typeA` AND of type `typeB`. A
real-world example might be `InterfaceA+InterfaceB` to accept only an object
that implements both `InterfaceA` AND `InterfaceB`.

### Extension types

    :ClassName
    :ClassName{attribute:value,...}
    :ClassName<typeA,typeB,typeC,...>{attribute:value,...}

Extensions provide a means to expand the capabilies of Typhax with custom logic.

## White space

In general, Typhax does not care whether white space is used in type
specifications. However, the above documentation should serve as a recommended
style guide.

## Usage

*Typhax* includes a parser for type expressions, which produces a syntax tree of
the parsed type:

```php
use Eloquent\Typhax\Parser\TypeParser;
use Eloquent\Typhax\Renderer\CondensedTypeRenderer;

$parser = TypeParser::create();
$type = $parser->parse('primaryType<keyType,valueType>');

$renderer = CondensedTypeRenderer::create();
echo $renderer->render($type); // outputs 'primaryType<keyType,valueType>'
```

Also included is a comparator for determining whether two types are equivalent:

```php
use Eloquent\Typhax\Comparator\TypeEquivalenceComparator;

$typeA = $parser->parse('integer|string');
$typeB = $parser->parse('string|integer');
$typeC = $parser->parse('string|integer|null');

$comparator = TypeEquivalenceComparator::create();
var_dump($comparator->isEquivalent($typeA, $typeB)); // outputs 'bool(true)'
var_dump($comparator->isEquivalent($typeB, $typeC)); // outputs 'bool(false)'
```

[array]: http://php.net/array
[object]: http://www.php.net/manual/en/language.oop5.php
[resource]: http://php.net/resource
[traversable types]: #traversable-types
[traversable]: http://php.net/traversable
