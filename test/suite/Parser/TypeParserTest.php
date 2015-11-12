<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2015 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Parser;

use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\CallableType;
use Eloquent\Typhax\Type\ExtensionType;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\NumericType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\ResourceType;
use Eloquent\Typhax\Type\StreamType;
use Eloquent\Typhax\Type\StringableType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use Eloquent\Typhax\Type\Type;
use PHPUnit_Framework_TestCase;
use ReflectionObject;

/**
 * @covers \Eloquent\Typhax\Parser\TypeParser
 */
class TypeParserTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->subject = TypeParser::create();
    }

    public function parserData()
    {
        $data = array();

        $source = ' foo end ';
        $offset = 4;
        $expected = new ObjectType('foo');
        $data['Basic example'] = array($expected, $offset, $source);

        $source = ' foo < bar > end ';
        $offset = 12;
        $expected = new TraversableType(
            new ObjectType('foo'),
            null,
            new ObjectType('bar')
        );
        $data['Simple traversable 1'] = array($expected, $offset, $source);

        $source = ' foo < bar , baz > end ';
        $offset = 18;
        $expected = new TraversableType(
            new ObjectType('foo'),
            new ObjectType('bar'),
            new ObjectType('baz')
        );
        $data['Simple traversable 2'] = array($expected, $offset, $source);

        $source = ' foo < bar , baz < qux , doom > > end ';
        $offset = 33;
        $expected = new TraversableType(
            new ObjectType('foo'),
            new ObjectType('bar'),
            new TraversableType(
                new ObjectType('baz'),
                new ObjectType('qux'),
                new ObjectType('doom')
            )
        );
        $data['Nested subtypes'] = array($expected, $offset, $source);

        $source = ' foo | bar end ';
        $offset = 10;
        $expected = new OrType(array(
            new ObjectType('foo'),
            new ObjectType('bar'),
        ));
        $data['Basic composite OR'] = array($expected, $offset, $source);

        $source = ' foo + bar end ';
        $offset = 10;
        $expected = new AndType(array(
            new ObjectType('foo'),
            new ObjectType('bar'),
        ));
        $data['Basic composite AND'] = array($expected, $offset, $source);

        $source = ' foo + bar + baz end ';
        $offset = 16;
        $expected = new AndType(array(
            new ObjectType('foo'),
            new ObjectType('bar'),
            new ObjectType('baz'),
        ));
        $data['Chained composite AND'] = array($expected, $offset, $source);

        $source = ' foo | bar + baz end ';
        $offset = 16;
        $expected = new OrType(array(
            new ObjectType('foo'),
            new AndType(array(
                new ObjectType('bar'),
                new ObjectType('baz'),
            )),
        ));
        $data['Composite precedence'] = array($expected, $offset, $source);

        $source = ' foo | bar + baz ';
        $offset = 16;
        $expected = new OrType(array(
            new ObjectType('foo'),
            new AndType(array(
                new ObjectType('bar'),
                new ObjectType('baz'),
            )),
        ));
        $data['Composite precedence at end'] = array($expected, $offset, $source);

        $source = ' foo < bar | baz , qux | doom > end ';
        $offset = 31;
        $expected = new TraversableType(
            new ObjectType('foo'),
            new OrType(array(
                new ObjectType('bar'),
                new ObjectType('baz'),
            )),
            new OrType(array(
                new ObjectType('qux'),
                new ObjectType('doom'),
            ))
        );
        $data['Composite types nested inside traversable'] = array($expected, $offset, $source);

        $source = ' tuple < bar | baz , qux | doom > end ';
        $offset = 33;
        $expected = new TupleType(array(
            new OrType(array(
                new ObjectType('bar'),
                new ObjectType('baz'),
            )),
            new OrType(array(
                new ObjectType('qux'),
                new ObjectType('doom'),
            )),
        ));
        $data['Composite types nested inside tuple'] = array($expected, $offset, $source);

        $source = ' foo | bar < baz , qux > end ';
        $offset = 24;
        $expected = new OrType(array(
            new ObjectType('foo'),
            new TraversableType(
                new ObjectType('bar'),
                new ObjectType('baz'),
                new ObjectType('qux')
            ),
        ));
        $data['Traversable type nested inside composite'] = array($expected, $offset, $source);

        $source = ' boolean | callable | float | integer | null | numeric | object | string | stringable | mixed end ';
        $offset = 93;
        $expected = new OrType(array(
            new BooleanType(),
            new CallableType(),
            new FloatType(),
            new IntegerType(),
            new NullType(),
            new NumericType(),
            new ObjectType(),
            new StringType(),
            new StringableType(),
            new MixedType(),
        ));
        $data['Instrinsic types'] = array($expected, $offset, $source);

        $source = ' bool | callback | double | int | long | number | real | scalar end ';
        $offset = 63;
        $expected = new OrType(array(
            new BooleanType(),
            new CallableType(),
            new FloatType(),
            new IntegerType(),
            new IntegerType(),
            new OrType(array(
                new IntegerType(),
                new FloatType(),
            )),
            new FloatType(),
            new OrType(array(
                new IntegerType(),
                new FloatType(),
                new StringType(),
                new BooleanType(),
            )),
        ));
        $data['Intrinsic aliases'] = array($expected, $offset, $source);

        $source = ' array end ';
        $offset = 6;
        $expected = new TraversableType(
            new ArrayType(),
            null,
            new MixedType()
        );
        $data['Array'] = array($expected, $offset, $source);

        $source = ' tuple < foo , bar , baz > end ';
        $offset = 26;
        $expected = new TupleType(array(
            new ObjectType('foo'),
            new ObjectType('bar'),
            new ObjectType('baz'),
        ));
        $data['Tuple type'] = array($expected, $offset, $source);

        $source = ' resource end ';
        $offset = 9;
        $expected = new ResourceType();
        $data['Resource type'] = array($expected, $offset, $source);

        $source = ' resource { ofType : foo } end ';
        $offset = 26;
        $expected = new ResourceType('foo');
        $data['Resource type with ofType attribute'] = array($expected, $offset, $source);

        $source = ' resource { ofType : stream } end ';
        $offset = 29;
        $expected = new ResourceType('stream');
        $data['Resource type with ofType attribute of reserved type name'] = array($expected, $offset, $source);

        $source = ' stream end ';
        $offset = 7;
        $expected = new StreamType();
        $data['Stream type'] = array($expected, $offset, $source);

        $source = ' stream { readable: true, writable: false } end ';
        $offset = 43;
        $expected = new StreamType(true, false);
        $data['Stream type with attributes'] = array($expected, $offset, $source);

        $source = ' stream { writable: true } end ';
        $offset = 26;
        $expected = new StreamType(null, true);
        $data['Stream type with only writable attribute'] = array($expected, $offset, $source);

        $source = ' foo end ';
        $offset = 4;
        $expected = new ObjectType('foo');
        $data["Don't parse past end of type"] = array($expected, $offset, $source);

        $source = ' : Foo\Bar end ';
        $offset = 10;
        $expected = new ExtensionType(
            'Foo\Bar',
            array(),
            array()
        );
        $data['Parse extension type'] = array($expected, $offset, $source);

        $source = ' : Foo\Bar { foo: bar } end ';
        $offset = 23;
        $expected = new ExtensionType(
            'Foo\Bar',
            array(),
            array('foo' => 'bar')
        );
        $data['Parse extension type with attributes'] = array($expected, $offset, $source);

        $source = ' : Foo\Bar < integer > { foo: bar } end ';
        $offset = 35;
        $expected = new ExtensionType(
            'Foo\Bar',
            array(new IntegerType()),
            array('foo' => 'bar')
        );
        $data['Parse extension type with sub types'] = array($expected, $offset, $source);

        return $data;
    }

    /**
     * @dataProvider parserData
     */
    public function testParser(Type $expected, $offset, $source)
    {
        $this->assertEquals($expected, $this->subject->parse($source, $actualOffset));
        $this->assertSame($offset, $actualOffset);
    }

    public function testParseOffsetHandling()
    {
        $source = 'data boolean : Foo\Bar < integer > { foo: bar } data';
        $offset = 5;
        $expectedA = new BooleanType();
        $expectedB = new ExtensionType('Foo\Bar', array(new IntegerType()), array('foo' => 'bar'));

        $this->assertEquals($expectedA, $this->subject->parse($source, $offset));
        $this->assertSame(12, $offset);
        $this->assertEquals($expectedB, $this->subject->parse($source, $offset));
        $this->assertSame(47, $offset);
    }

    public function parserFailureData()
    {
        $data = array();

        $source = '';
        $expectedClass = __NAMESPACE__ . '\Exception\UnexpectedTokenException';
        $expectedMessage = 'Unexpected END at offset 0. Expected one of STRING, TYPE_NAME, NULL, COLON.';
        $data['Empty string'] = array($expectedClass, $expectedMessage, $source);

        $source = ' ';
        $expectedClass = __NAMESPACE__ . '\Exception\UnexpectedTokenException';
        $expectedMessage = 'Unexpected END at offset 0. Expected one of STRING, TYPE_NAME, NULL, COLON.';
        $data['Whitespace string'] = array($expectedClass, $expectedMessage, $source);

        $source = ' foo < > ';
        $expectedClass = __NAMESPACE__ . '\Exception\UnexpectedTokenException';
        $expectedMessage = 'Unexpected GREATER_THAN at offset 7. Expected one of STRING, TYPE_NAME, NULL, COLON.';
        $data['Empty type list'] = array($expectedClass, $expectedMessage, $source);

        $source = ' resource { } ';
        $expectedClass = __NAMESPACE__ . '\Exception\UnexpectedTokenException';
        $expectedMessage = 'Unexpected BRACE_CLOSE at offset 12. Expected one of STRING, STRING_QUOTED, TYPE_NAME, INTEGER, FLOAT, NULL, BOOLEAN_TRUE, BOOLEAN_FALSE, BRACE_OPEN, SQUARE_BRACKET_OPEN.';
        $data['Empty attributes'] = array($expectedClass, $expectedMessage, $source);

        $source = ' resource { foo : bar } ';
        $expectedClass = __NAMESPACE__ . '\Exception\UnsupportedAttributeException';
        $expectedMessage = "Unsupported attribute at offset 12. Type 'resource' does not support attribute 'foo'.";
        $data['Unsupported attributes'] = array($expectedClass, $expectedMessage, $source);

        $source = ' resource { foobar : baz } ';
        $expectedClass = __NAMESPACE__ . '\Exception\UnsupportedAttributeException';
        $expectedMessage = "Unsupported attribute at offset 12. Type 'resource' does not support attribute 'foobar'.";
        $data['Unsupported attributes offset calculation check'] = array($expectedClass, $expectedMessage, $source);

        $source = ' string < foo > ';
        $expectedClass = __NAMESPACE__ . '\Exception\UnexpectedTokenException';
        $expectedMessage = 'Unexpected LESS_THAN at offset 8. Expected one of BRACE_OPEN, PLUS, PIPE.';
        $data['Non-traversable as primary in a traversable'] = array($expectedClass, $expectedMessage, $source);

        $source = ' foo < bar , baz , qux , doom , splat > ';
        $expectedClass = __NAMESPACE__ . '\Exception\UnexpectedTokenException';
        $expectedMessage = 'Unexpected COMMA at offset 17. Expected GREATER_THAN.';
        $data['Traversable with too many types in type list'] = array($expectedClass, $expectedMessage, $source);

        return $data;
    }

    /**
     * @dataProvider parserFailureData
     */
    public function testParserFailure($expectedClass, $expectedMessage, $source)
    {
        $this->setExpectedException($expectedClass, $expectedMessage);
        $this->subject->parse($source);
    }

    public function hashData()
    {
        $data = array();

        // #0: Basic hash
        $source = ' { foo : "bar" , \'baz\' : 666 , qux : .666 , doom : null , splat : true , pop : false } ';
        $expected = array(
            'foo' => 'bar',
            'baz' => 666,
            'qux' => .666,
            'doom' => null,
            'splat' => true,
            'pop' => false,
        );
        $data[] = array($expected, $source);

        // #1: Nested hash and array
        $source = ' { foo : { bar : baz } , qux : [ doom, [ splat , pop ] ] } ';
        $expected = array(
            'foo' => array(
                'bar' => 'baz',
            ),
            'qux' => array(
                'doom',
                array(
                    'splat',
                    'pop',
                ),
            ),
        );
        $data[] = array($expected, $source);

        // #2: Empty hash and array
        $source = ' { foo : { } , bar : [ ] } ';
        $expected = array(
            'foo' => array(),
            'bar' => array(),
        );
        $data[] = array($expected, $source);

        return $data;
    }

    /**
     * @dataProvider hashData
     */
    public function testParseHash(array $expected, $source)
    {
        $lexer = new TypeLexer();
        $tokens = $lexer->tokens($source, 0);
        $index = 0;
        $reflector = new ReflectionObject($this->subject);
        $method = $reflector->getMethod('parseHash');
        $method->setAccessible(true);
        $arguments = array(&$tokens, &$index);
        $actual = $method->invokeArgs($this->subject, $arguments);

        $this->assertEquals($expected, $actual);
    }
}
