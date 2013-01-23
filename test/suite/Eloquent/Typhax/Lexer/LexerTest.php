<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Lexer;

use PHPUnit_Framework_TestCase;

class LexerTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_lexer = new Lexer;
    }

    public function intrinsicTypeNameData()
    {
        return array(
            array('array'),
            array('boolean'),
            array('callable'),
            array('float'),
            array('integer'),
            array('mixed'),
            array('object'),
            array('resource'),
            array('stream'),
            array('string'),
            array('stringable'),
            array('tuple'),

            array('bool'),
            array('callback'),
            array('double'),
            array('int'),
            array('long'),
            array('number'),
            array('numeric'),
            array('real'),
            array('scalar'),
        );
    }

    /**
     * @dataProvider intrinsicTypeNameData
     */
    public function testIntrinsicTypesNames($typeName)
    {
        $expected = array(
            new Token(Token::TOKEN_TYPE_NAME, $typeName),
        );

        $this->assertEquals($expected, $this->_lexer->tokens($typeName));
    }

    public function tokenData()
    {
        $data = array();

        // #0: Basic type
        $source = 'type';
        $expected = array(
            new Token(Token::TOKEN_STRING, 'type'),
        );
        $data[] = array($expected, $source);

        // #1: Type with sub-type
        $source = 'type<subType>';
        $expected = array(
            new Token(Token::TOKEN_STRING, 'type'),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_STRING, 'subType'),
            new Token(Token::TOKEN_GREATER_THAN, '>'),
        );
        $data[] = array($expected, $source);

        // #2: Type with multiple sub-types
        $source = 'type<subType, subType, subType>';
        $expected = array(
            new Token(Token::TOKEN_STRING, 'type'),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_STRING, 'subType'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'subType'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'subType'),
            new Token(Token::TOKEN_GREATER_THAN, '>'),
        );
        $data[] = array($expected, $source);

        // #3: Composite OR type
        $source = 'type|type';
        $expected = array(
            new Token(Token::TOKEN_STRING, 'type'),
            new Token(Token::TOKEN_PIPE, '|'),
            new Token(Token::TOKEN_STRING, 'type'),
        );
        $data[] = array($expected, $source);

        // #4: Composite AND type
        $source = 'type+type';
        $expected = array(
            new Token(Token::TOKEN_STRING, 'type'),
            new Token(Token::TOKEN_PLUS, '+'),
            new Token(Token::TOKEN_STRING, 'type'),
        );
        $data[] = array($expected, $source);

        // #5: Treatment of unsupported tokens as strings
        $source = 'type-type';
        $expected = array(
            new Token(Token::TOKEN_STRING, 'type-type'),
        );
        $data[] = array($expected, $source);

        // #6: Dynamic type with basic attributes and quoted strings
        $source = 'type{foo: bar, \'baz\': "qux"}';
        $expected = array(
            new Token(Token::TOKEN_STRING, 'type'),
            new Token(Token::TOKEN_BRACE_OPEN, '{'),
            new Token(Token::TOKEN_STRING, 'foo'),
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'bar'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING_QUOTED, "'baz'"),
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING_QUOTED, '"qux"'),
            new Token(Token::TOKEN_BRACE_CLOSE, '}'),
        );
        $data[] = array($expected, $source);

        // #7: Dynamic type with array attributes
        $source = 'type{foo: [bar, baz], qux: {doom: splat, pip: pop}}';
        $expected = array(
            new Token(Token::TOKEN_STRING, 'type'),
            new Token(Token::TOKEN_BRACE_OPEN, '{'),
            new Token(Token::TOKEN_STRING, 'foo'),
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_SQUARE_BRACKET_OPEN, '['),
            new Token(Token::TOKEN_STRING, 'bar'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'baz'),
            new Token(Token::TOKEN_SQUARE_BRACKET_CLOSE, ']'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'qux'),
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_BRACE_OPEN, '{'),
            new Token(Token::TOKEN_STRING, 'doom'),
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'splat'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'pip'),
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'pop'),
            new Token(Token::TOKEN_BRACE_CLOSE, '}'),
            new Token(Token::TOKEN_BRACE_CLOSE, '}'),
        );
        $data[] = array($expected, $source);

        // #8: Nested array
        $source = '[foo, [bar, baz]]';
        $expected = array(
            new Token(Token::TOKEN_SQUARE_BRACKET_OPEN, '['),
            new Token(Token::TOKEN_STRING, 'foo'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_SQUARE_BRACKET_OPEN, '['),
            new Token(Token::TOKEN_STRING, 'bar'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'baz'),
            new Token(Token::TOKEN_SQUARE_BRACKET_CLOSE, ']'),
            new Token(Token::TOKEN_SQUARE_BRACKET_CLOSE, ']'),
        );
        $data[] = array($expected, $source);

        // #9: Nested hash
        $source = '{foo: bar, {bar: baz, qux: doom}}';
        $expected = array(
            new Token(Token::TOKEN_BRACE_OPEN, '{'),
            new Token(Token::TOKEN_STRING, 'foo'),
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'bar'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_BRACE_OPEN, '{'),
            new Token(Token::TOKEN_STRING, 'bar'),
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'baz'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'qux'),
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'doom'),
            new Token(Token::TOKEN_BRACE_CLOSE, '}'),
            new Token(Token::TOKEN_BRACE_CLOSE, '}'),
        );
        $data[] = array($expected, $source);

        // #10: Treatment of numbers
        $source = '1, 1.0';
        $expected = array(
            new Token(Token::TOKEN_INTEGER, '1'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_FLOAT, '1.0'),
        );
        $data[] = array($expected, $source);

        // #11: Treatment of booleans and nulls
        $source = 'true, TRUE, True, false, null';
        $expected = array(
            new Token(Token::TOKEN_BOOLEAN_TRUE, 'true'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_BOOLEAN_TRUE, 'TRUE'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_BOOLEAN_TRUE, 'True'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_BOOLEAN_FALSE, 'false'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_NULL, 'null'),
        );
        $data[] = array($expected, $source);

        // #12: Treatment of unsupported tokens containing supported tokens
        $source = '+= ++ || ?> %> {$ ${ => >= <> <= -> <?php <? <% <?= <%= |= :: << <<= >> >>= <<<';
        $expected = array(
            new Token(Token::TOKEN_PLUS, '+'),
            new Token(Token::TOKEN_STRING, '='),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_PLUS, '+'),
            new Token(Token::TOKEN_PLUS, '+'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_PIPE, '|'),
            new Token(Token::TOKEN_PIPE, '|'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, '?'),
            new Token(Token::TOKEN_GREATER_THAN, '>'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, '%'),
            new Token(Token::TOKEN_GREATER_THAN, '>'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_BRACE_OPEN, '{'),
            new Token(Token::TOKEN_STRING, '$'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, '$'),
            new Token(Token::TOKEN_BRACE_OPEN, '{'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, '='),
            new Token(Token::TOKEN_GREATER_THAN, '>'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_GREATER_THAN, '>'),
            new Token(Token::TOKEN_STRING, '='),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_GREATER_THAN, '>'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_STRING, '='),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, '-'),
            new Token(Token::TOKEN_GREATER_THAN, '>'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_STRING, '?php'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_STRING, '?'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_STRING, '%'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_STRING, '?='),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_STRING, '%='),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_PIPE, '|'),
            new Token(Token::TOKEN_STRING, '='),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_STRING, '='),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_GREATER_THAN, '>'),
            new Token(Token::TOKEN_GREATER_THAN, '>'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_GREATER_THAN, '>'),
            new Token(Token::TOKEN_GREATER_THAN, '>'),
            new Token(Token::TOKEN_STRING, '='),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_LESS_THAN, '<'),
        );
        $data[] = array($expected, $source);

        // #13: Treatment of quoted strings containing variables etc.
        $source = '"foo $bar $baz[0] $qux->doom {$great} ${great} {$square->width} {$arr[\'key\']} {$arr[4][3]} {$arr[\'foo\'][3]} {$obj->values[3]->name} {${$name}} {${getName()}} {${$object->getName()}} \\\\\\""';
        $expected = array(
            new Token(Token::TOKEN_STRING_QUOTED, $source),
        );
        $data[] = array($expected, $source);

        // #14: Nested sub-types
        $source = 'type<subType, subType<subSubType, subSubType>>';
        $expected = array(
            new Token(Token::TOKEN_STRING, 'type'),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_STRING, 'subType'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'subType'),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_STRING, 'subSubType'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'subSubType'),
            new Token(Token::TOKEN_GREATER_THAN, '>'),
            new Token(Token::TOKEN_GREATER_THAN, '>'),
        );
        $data[] = array($expected, $source);

        // #15: Type with attributes and subtypes
        $source = 'type<subType, subType>{foo: bar}';
        $expected = array(
            new Token(Token::TOKEN_STRING, 'type'),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_STRING, 'subType'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'subType'),
            new Token(Token::TOKEN_GREATER_THAN, '>'),
            new Token(Token::TOKEN_BRACE_OPEN, '{'),
            new Token(Token::TOKEN_STRING, 'foo'),
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'bar'),
            new Token(Token::TOKEN_BRACE_CLOSE, '}'),
        );
        $data[] = array($expected, $source);

        // #16: Namespaced type name
        $source = 'Foo\Bar\Baz';
        $expected = array(
            new Token(Token::TOKEN_STRING, 'Foo\Bar\Baz'),
        );
        $data[] = array($expected, $source);

        // #17: Basic extension type
        $source = ':Foo';
        $expected = array(
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_STRING, 'Foo'),
        );
        $data[] = array($expected, $source);

        // #18: Namespaced extension type
        $source = ':Foo\Bar\Baz';
        $expected = array(
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_STRING, 'Foo\Bar\Baz'),
        );
        $data[] = array($expected, $source);

        // #19: Namespaced extension type with attributes
        $source = ':Foo\Bar\Baz{foo: bar}';
        $expected = array(
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_STRING, 'Foo\Bar\Baz'),
            new Token(Token::TOKEN_BRACE_OPEN, '{'),
            new Token(Token::TOKEN_STRING, 'foo'),
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'bar'),
            new Token(Token::TOKEN_BRACE_CLOSE, '}'),
        );
        $data[] = array($expected, $source);

        // #19: Namespaced extension type with subtypes and attributes
        $source = ':Foo\Bar\Baz<subType, subType>{foo: bar}';
        $expected = array(
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_STRING, 'Foo\Bar\Baz'),
            new Token(Token::TOKEN_LESS_THAN, '<'),
            new Token(Token::TOKEN_STRING, 'subType'),
            new Token(Token::TOKEN_COMMA, ','),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'subType'),
            new Token(Token::TOKEN_GREATER_THAN, '>'),
            new Token(Token::TOKEN_BRACE_OPEN, '{'),
            new Token(Token::TOKEN_STRING, 'foo'),
            new Token(Token::TOKEN_COLON, ':'),
            new Token(Token::TOKEN_WHITESPACE, ' '),
            new Token(Token::TOKEN_STRING, 'bar'),
            new Token(Token::TOKEN_BRACE_CLOSE, '}'),
        );
        $data[] = array($expected, $source);

        return $data;
    }

    /**
     * @dataProvider tokenData
     */
    public function testTokens(array $expected, $source)
    {
        $this->assertEquals($expected, $this->_lexer->tokens($source));
    }
}
