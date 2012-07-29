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
use Eloquent\Typhax\Type\NumberType;
use Eloquent\Typhax\Type\NumericType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\ResourceType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use Eloquent\Typhax\Type\Type;

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
        $source = ' array | boolean | callback | float | integer | null | number | numeric | object | string | mixed ';
        $expected = new OrType(array(
            new ArrayType,
            new BooleanType,
            new CallbackType,
            new FloatType,
            new IntegerType,
            new NullType,
            new NumberType,
            new NumericType,
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
        $source = ' resource ( ofType : foo ) ';
        $expected = new ResourceType('foo');
        $data[] = array($expected, $source);

        // #9: Basic attributes
        // $source = ' foo ( bar : "baz" , \'qux\' : 666 , doom : .666 , splat : null , pip : true , pop : false ) ';
        // $expected = new ObjectType('foo');
        // $expected->setAttribute('bar', 'baz');
        // $expected->setAttribute('qux', 666);
        // $expected->setAttribute('doom', .666);
        // $expected->setAttribute('splat', null);
        // $expected->setAttribute('pip', true);
        // $expected->setAttribute('pop', false);
        // $data[] = array($expected, $source);

        // // #9: Nested hash attribute
        // $source = 'foo(bar:{baz:{qux:doom}})';
        // $expected = new ObjectType('foo');
        // $expected->setAttribute('bar', array(
        //     'baz' => array(
        //         'qux' => 'doom',
        //     ),
        // ));
        // $data[] = array($expected, $source);

        // // #10: Nested array attribute
        // $source = 'foo(bar:[baz,[qux,doom]])';
        // $expected = new ObjectType('foo');
        // $expected->setAttribute('bar', array(
        //     'baz',
        //     array(
        //         'qux',
        //         'doom',
        //     ),
        // ));
        // $data[] = array($expected, $source);

        // // #11: Empty hash and array
        // $source = 'foo(bar:{},baz:[])';
        // $expected = new ObjectType('foo');
        // $expected->setAttribute('bar', array());
        // $expected->setAttribute('baz', array());
        // $data[] = array($expected, $source);


        // // #15: Mixed subtypes and attributes
        // $source = 'foo<bar,baz>(qux:doom)';
        // $expected = new ObjectType('foo');
        // $expected->addSubType(new ObjectType('bar'));
        // $expected->addSubType(new ObjectType('baz'));
        // $expected->setAttribute('qux', 'doom');
        // $data[] = array($expected, $source);

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
        $expectedMessage = 'Unexpected END at position 0. Expected STRING.';
        $data[] = array($expectedClass, $expectedMessage, $source);

        // #1: Type followed by non-attributes, non-subtypes
        $source = 'type{';
        $expectedClass = __NAMESPACE__.'\Exception\UnexpectedTokenException';
        $expectedMessage = 'Unexpected BRACE_OPEN at position 5. Expected END.';
        $data[] = array($expectedClass, $expectedMessage, $source);

        // TODO: test for failure on empty type lists and attributes.

        return $data;
    }

    // /**
    //  * @dataProvider parserFailureData
    //  */
    // public function testParserFailure($expectedClass, $expectedMessage, $source)
    // {
    //     $tokens = $this->_lexer->tokens($source);
    //     $parser = new Parser;

    //     $this->setExpectedException($expectedClass, $expectedMessage);
    //     $parser->parse($tokens);
    // }

}
