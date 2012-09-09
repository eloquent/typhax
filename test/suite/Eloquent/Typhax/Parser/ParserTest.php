<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Parser;

use Eloquent\Typhax\Lexer\Lexer;
use Eloquent\Typhax\Lexer\Token;
use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\CallableType;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\NumericType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\ResourceType;
use Eloquent\Typhax\Type\StreamType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\StringableType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use Eloquent\Typhax\Type\Type;
use PHPUnit_Framework_TestCase;
use ReflectionObject;

class ParserTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_lexer = new Lexer;
    }

    public function parserData()
    {
        $data = array();

        $source = ' foo ';
        $position = 6;
        $expected = new ObjectType('foo');
        $data['Basic example'] = array($expected, $position, $source);

        $source = ' foo < bar > ';
        $position = 14;
        $expected = new TraversableType(
            new ObjectType('foo'),
            new MixedType,
            new ObjectType('bar')
        );
        $data['Simple traversable 1'] = array($expected, $position, $source);

        $source = ' foo < bar , baz > ';
        $position = 20;
        $expected = new TraversableType(
            new ObjectType('foo'),
            new ObjectType('bar'),
            new ObjectType('baz')
        );
        $data['Simple traversable 2'] = array($expected, $position, $source);

        $source = ' foo < bar , baz < qux , doom > > ';
        $position = 35;
        $expected = new TraversableType(
            new ObjectType('foo'),
            new ObjectType('bar'),
            new TraversableType(
                new ObjectType('baz'),
                new ObjectType('qux'),
                new ObjectType('doom')
            )
        );
        $data['Nested subtypes'] = array($expected, $position, $source);

        $source = ' foo | bar ';
        $position = 12;
        $expected = new OrType(array(
            new ObjectType('foo'),
            new ObjectType('bar'),
        ));
        $data['Basic composite OR'] = array($expected, $position, $source);

        $source = ' foo + bar ';
        $position = 12;
        $expected = new AndType(array(
            new ObjectType('foo'),
            new ObjectType('bar'),
        ));
        $data['Basic composite AND'] = array($expected, $position, $source);

        $source = ' foo + bar + baz ';
        $position = 18;
        $expected = new AndType(array(
            new ObjectType('foo'),
            new ObjectType('bar'),
            new ObjectType('baz'),
        ));
        $data['Chained composite AND'] = array($expected, $position, $source);

        $source = ' foo | bar + baz ';
        $position = 18;
        $expected = new OrType(array(
            new ObjectType('foo'),
            new AndType(array(
                new ObjectType('bar'),
                new ObjectType('baz'),
            ))
        ));
        $data['Composite precedence'] = array($expected, $position, $source);

        $source = ' foo < bar | baz , qux | doom > ';
        $position = 33;
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
        $data['Composite types nested inside traversable'] = array($expected, $position, $source);

        $source = ' tuple < bar | baz , qux | doom > ';
        $position = 35;
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
        $data['Composite types nested inside tuple'] = array($expected, $position, $source);

        $source = ' foo | bar < baz , qux > ';
        $position = 25;
        $expected = new OrType(array(
            new ObjectType('foo'),
            new TraversableType(
                new ObjectType('bar'),
                new ObjectType('baz'),
                new ObjectType('qux')
            ),
        ));
        $data['Traversable type nested inside composite'] = array($expected, $position, $source);

        $source = ' boolean | callable | float | integer | null | numeric | object | string | stringable | mixed ';
        $position = 95;
        $expected = new OrType(array(
            new BooleanType,
            new CallableType,
            new FloatType,
            new IntegerType,
            new NullType,
            new NumericType,
            new ObjectType,
            new StringType,
            new StringableType,
            new MixedType,
        ));
        $data['Instrinsic types'] = array($expected, $position, $source);

        $source = ' bool | callback | double | int | long | number | real | scalar ';
        $position = 65;
        $expected = new OrType(array(
            new BooleanType,
            new CallableType,
            new FloatType,
            new IntegerType,
            new IntegerType,
            new OrType(array(
                new IntegerType,
                new FloatType,
            )),
            new FloatType,
            new OrType(array(
                new IntegerType,
                new FloatType,
                new StringType,
                new BooleanType,
            )),
        ));
        $data['Intrinsic aliases'] = array($expected, $position, $source);

        $source = ' array ';
        $position = 8;
        $expected = new TraversableType(
            new ArrayType,
            new MixedType,
            new MixedType
        );
        $data['Array'] = array($expected, $position, $source);

        $source = ' tuple < foo , bar , baz > ';
        $position = 28;
        $expected = new TupleType(array(
            new ObjectType('foo'),
            new ObjectType('bar'),
            new ObjectType('baz'),
        ));
        $data['Tuple type'] = array($expected, $position, $source);

        $source = ' resource ';
        $position = 11;
        $expected = new ResourceType;
        $data['Resource type'] = array($expected, $position, $source);

        $source = ' resource { ofType : foo } ';
        $position = 28;
        $expected = new ResourceType('foo');
        $data['Resource type with ofType attribute'] = array($expected, $position, $source);

        $source = ' resource { ofType : stream } ';
        $position = 31;
        $expected = new ResourceType('stream');
        $data['Resource type with ofType attribute of reserved type name'] = array($expected, $position, $source);

        $source = ' stream ';
        $position = 9;
        $expected = new StreamType;
        $data['Stream type'] = array($expected, $position, $source);

        $source = ' stream { readable: true, writable: false } ';
        $position = 45;
        $expected = new StreamType(true, false);
        $data['Stream type with attributes'] = array($expected, $position, $source);

        $source = ' stream { writable: true } ';
        $position = 28;
        $expected = new StreamType(null, true);
        $data['Stream type with only writable attribute'] = array($expected, $position, $source);

        $source = ' foo bar ';
        $position = 6;
        $expected = new ObjectType('foo');
        $data["Don't parse past end of type"] = array($expected, $position, $source);

        return $data;
    }

    /**
     * @dataProvider parserData
     */
    public function testParser(Type $expected, $position, $source)
    {
        $tokens = $this->_lexer->tokens($source);
        $parser = new Parser;

        $this->assertEquals($expected, $parser->parse($source, $actualPosition));
        $this->assertSame($position, $actualPosition);
    }

    public function parserFailureData()
    {
        $data = array();

        // #0: Empty string
        $source = '';
        $expectedClass = __NAMESPACE__.'\Exception\UnexpectedTokenException';
        $expectedMessage = 'Unexpected END at position 0. Expected one of STRING, TYPE_NAME, NULL.';
        $data[] = array($expectedClass, $expectedMessage, $source);

        // #1: Whitespace string
        $source = ' ';
        $expectedClass = __NAMESPACE__.'\Exception\UnexpectedTokenException';
        $expectedMessage = 'Unexpected END at position 2. Expected one of STRING, TYPE_NAME, NULL.';
        $data[] = array($expectedClass, $expectedMessage, $source);

        // #2: Empty type list
        $source = ' foo < > ';
        $expectedClass = __NAMESPACE__.'\Exception\UnexpectedTokenException';
        $expectedMessage = 'Unexpected GREATER_THAN at position 8. Expected one of STRING, TYPE_NAME, NULL.';
        $data[] = array($expectedClass, $expectedMessage, $source);

        // #3: Empty attributes
        $source = ' resource { } ';
        $expectedClass = __NAMESPACE__.'\Exception\UnexpectedTokenException';
        $expectedMessage = 'Unexpected BRACE_CLOSE at position 13. Expected one of STRING, STRING_QUOTED, TYPE_NAME, INTEGER, FLOAT, NULL, BOOLEAN_TRUE, BOOLEAN_FALSE, BRACE_OPEN, SQUARE_BRACKET_OPEN.';
        $data[] = array($expectedClass, $expectedMessage, $source);

        // #4: Unsupported attributes
        $source = ' resource { foo : bar } ';
        $expectedClass = __NAMESPACE__.'\Exception\UnsupportedAttributeException';
        $expectedMessage = "Unsupported attribute at position 13. Type 'resource' does not support attribute 'foo'.";
        $data[] = array($expectedClass, $expectedMessage, $source);

        // #5: Unsupported attributes position calculation check
        $source = ' resource { foobar : baz } ';
        $expectedClass = __NAMESPACE__.'\Exception\UnsupportedAttributeException';
        $expectedMessage = "Unsupported attribute at position 13. Type 'resource' does not support attribute 'foobar'.";
        $data[] = array($expectedClass, $expectedMessage, $source);

        // #6: Non-traversable as primary in a traversable
        $source = ' string < foo > ';
        $expectedClass = __NAMESPACE__.'\Exception\UnexpectedTokenException';
        $expectedMessage = 'Unexpected LESS_THAN at position 9. Expected one of BRACE_OPEN, PLUS, PIPE.';
        $data[] = array($expectedClass, $expectedMessage, $source);

        // #7: Traversable with too many types in type list
        $source = ' foo < bar , baz , qux , doom , splat > ';
        $expectedClass = __NAMESPACE__.'\Exception\UnexpectedTokenException';
        $expectedMessage = 'Unexpected COMMA at position 18. Expected GREATER_THAN.';
        $data[] = array($expectedClass, $expectedMessage, $source);

        return $data;
    }

    /**
     * @dataProvider parserFailureData
     */
    public function testParserFailure($expectedClass, $expectedMessage, $source)
    {
        $parser = new Parser;

        $this->setExpectedException($expectedClass, $expectedMessage);
        $parser->parse($source);
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
        $tokens = $this->_lexer->tokens($source);
        $parser = new Parser;
        $reflector = new ReflectionObject($parser);
        $method = $reflector->getMethod('parseHash');
        $method->setAccessible(true);
        $arguments = array(&$tokens);
        $actual = $method->invokeArgs($parser, $arguments);

        $this->assertEquals($expected, $actual);
    }

}
