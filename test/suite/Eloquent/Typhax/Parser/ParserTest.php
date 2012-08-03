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
use Eloquent\Typhax\Type\CallbackType;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\ResourceType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use Eloquent\Typhax\Type\Type;
use ReflectionObject;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_lexer = new Lexer;
    }

    public function parserData()
    {
        $data = array();

        // #0: Basic example
        $source = ' foo ';
        $expected = new ObjectType('foo');
        $data[] = array($expected, $source);

        // #1: Simple traversable
        $source = ' foo < bar > ';
        $expected = new TraversableType(
            new ObjectType('foo'),
            new MixedType,
            new ObjectType('bar')
        );
        $data[] = array($expected, $source);

        // #2: Simple traversable
        $source = ' foo < bar , baz > ';
        $expected = new TraversableType(
            new ObjectType('foo'),
            new ObjectType('bar'),
            new ObjectType('baz')
        );
        $data[] = array($expected, $source);

        // #3: Nested subtypes
        $source = ' foo < bar , baz < qux , doom > > ';
        $expected = new TraversableType(
            new ObjectType('foo'),
            new ObjectType('bar'),
            new TraversableType(
                new ObjectType('baz'),
                new ObjectType('qux'),
                new ObjectType('doom')
            )
        );
        $data[] = array($expected, $source);

        // #4: Basic composite OR
        $source = ' foo | bar ';
        $expected = new OrType(array(
            new ObjectType('foo'),
            new ObjectType('bar'),
        ));
        $data[] = array($expected, $source);

        // #5: Basic composite AND
        $source = ' foo & bar ';
        $expected = new AndType(array(
            new ObjectType('foo'),
            new ObjectType('bar'),
        ));
        $data[] = array($expected, $source);

        // #6: Chained composite AND
        $source = ' foo & bar & baz ';
        $expected = new AndType(array(
            new ObjectType('foo'),
            new ObjectType('bar'),
            new ObjectType('baz'),
        ));
        $data[] = array($expected, $source);

        // #7: Composite precedence
        $source = ' foo | bar & baz ';
        $expected = new OrType(array(
            new ObjectType('foo'),
            new AndType(array(
                new ObjectType('bar'),
                new ObjectType('baz'),
            ))
        ));
        $data[] = array($expected, $source);

        // #8: Test basic types
        $source = ' array | boolean | callback | float | integer | null | object | string | mixed ';
        $expected = new OrType(array(
            new ArrayType,
            new BooleanType,
            new CallbackType,
            new FloatType,
            new IntegerType,
            new NullType,
            new ObjectType,
            new StringType,
            new MixedType,
        ));
        $data[] = array($expected, $source);

        // #9: Test tuple type.
        $source = ' tuple < foo , bar , baz > ';
        $expected = new TupleType(array(
            new ObjectType('foo'),
            new ObjectType('bar'),
            new ObjectType('baz'),
        ));
        $data[] = array($expected, $source);

        // #10: Test resource
        $source = ' resource ';
        $expected = new ResourceType;
        $data[] = array($expected, $source);

        // #11: Test resource with ofType attribute.
        $source = ' resource { ofType : foo } ';
        $expected = new ResourceType('foo');
        $data[] = array($expected, $source);

        return $data;
    }

    /**
     * @dataProvider parserData
     */
    public function testParser(Type $expected, $source)
    {
        $tokens = $this->_lexer->tokens($source);
        $parser = new Parser;
        $actual = $parser->parse($tokens);

        $this->assertEquals($expected, $actual);
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
        $expectedMessage = 'Unexpected END at position 1. Expected one of STRING, TYPE_NAME, NULL.';
        $data[] = array($expectedClass, $expectedMessage, $source);

        // #2: Empty type list
        $source = ' foo < > ';
        $expectedClass = __NAMESPACE__.'\Exception\UnexpectedTokenException';
        $expectedMessage = 'Unexpected GREATER_THAN at position 8. Expected one of STRING, TYPE_NAME, NULL.';
        $data[] = array($expectedClass, $expectedMessage, $source);

        // #3: Empty attributes
        $source = ' resource { } ';
        $expectedClass = __NAMESPACE__.'\Exception\UnexpectedTokenException';
        $expectedMessage = 'Unexpected BRACE_CLOSE at position 13. Expected one of STRING, STRING_QUOTED, INTEGER, FLOAT, NULL, BOOLEAN_TRUE, BOOLEAN_FALSE, BRACE_OPEN, SQUARE_BRACKET_OPEN.';
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
        $expectedMessage = 'Unexpected LESS_THAN at position 9. Expected one of BRACE_OPEN, AND, PIPE.';
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
        $tokens = $this->_lexer->tokens($source);
        $parser = new Parser;

        $this->setExpectedException($expectedClass, $expectedMessage);
        $parser->parse($tokens);
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
