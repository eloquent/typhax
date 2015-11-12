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

use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Typhax\Parser\TypeLexer
 */
class TypeLexerTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->lexer = new TypeLexer();
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
            new TypeToken(TypeToken::TOKEN_TYPE_NAME, $typeName),
        );

        $this->assertEquals($expected, $this->lexer->tokens($typeName, 0));
    }

    public function tokenData()
    {
        $data = array();

        $source = 'type';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_STRING, 'type'),
        );
        $data['Basic type'] = array($expected, $source);

        $source = 'type<subType>';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_STRING, 'type'),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_STRING, 'subType'),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
        );
        $data['Type with sub-type'] = array($expected, $source);

        $source = 'type<subType, subType, subType>';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_STRING, 'type'),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_STRING, 'subType'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'subType'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'subType'),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
        );
        $data['Type with multiple sub-types'] = array($expected, $source);

        $source = 'type|type';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_STRING, 'type'),
            new TypeToken(TypeToken::TOKEN_PIPE, '|'),
            new TypeToken(TypeToken::TOKEN_STRING, 'type'),
        );
        $data['Composite OR type'] = array($expected, $source);

        $source = 'type+type';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_STRING, 'type'),
            new TypeToken(TypeToken::TOKEN_PLUS, '+'),
            new TypeToken(TypeToken::TOKEN_STRING, 'type'),
        );
        $data['Composite AND type'] = array($expected, $source);

        $source = 'type-type';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_STRING, 'type-type'),
        );
        $data['Treatment of unsupported tokens as strings'] = array($expected, $source);

        $source = 'type{foo: bar, \'baz\': "qux"}';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_STRING, 'type'),
            new TypeToken(TypeToken::TOKEN_BRACE_OPEN, '{'),
            new TypeToken(TypeToken::TOKEN_STRING, 'foo'),
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'bar'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING_QUOTED, "'baz'"),
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING_QUOTED, '"qux"'),
            new TypeToken(TypeToken::TOKEN_BRACE_CLOSE, '}'),
        );
        $data['Dynamic type with basic attributes and quoted strings'] = array($expected, $source);

        $source = 'type{foo: [bar, baz], qux: {doom: splat, pip: pop}}';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_STRING, 'type'),
            new TypeToken(TypeToken::TOKEN_BRACE_OPEN, '{'),
            new TypeToken(TypeToken::TOKEN_STRING, 'foo'),
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_SQUARE_BRACKET_OPEN, '['),
            new TypeToken(TypeToken::TOKEN_STRING, 'bar'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'baz'),
            new TypeToken(TypeToken::TOKEN_SQUARE_BRACKET_CLOSE, ']'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'qux'),
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_BRACE_OPEN, '{'),
            new TypeToken(TypeToken::TOKEN_STRING, 'doom'),
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'splat'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'pip'),
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'pop'),
            new TypeToken(TypeToken::TOKEN_BRACE_CLOSE, '}'),
            new TypeToken(TypeToken::TOKEN_BRACE_CLOSE, '}'),
        );
        $data['Dynamic type with array attributes'] = array($expected, $source);

        $source = '[foo, [bar, baz]]';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_SQUARE_BRACKET_OPEN, '['),
            new TypeToken(TypeToken::TOKEN_STRING, 'foo'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_SQUARE_BRACKET_OPEN, '['),
            new TypeToken(TypeToken::TOKEN_STRING, 'bar'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'baz'),
            new TypeToken(TypeToken::TOKEN_SQUARE_BRACKET_CLOSE, ']'),
            new TypeToken(TypeToken::TOKEN_SQUARE_BRACKET_CLOSE, ']'),
        );
        $data['Nested array'] = array($expected, $source);

        $source = '{foo: bar, {bar: baz, qux: doom}}';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_BRACE_OPEN, '{'),
            new TypeToken(TypeToken::TOKEN_STRING, 'foo'),
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'bar'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_BRACE_OPEN, '{'),
            new TypeToken(TypeToken::TOKEN_STRING, 'bar'),
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'baz'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'qux'),
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'doom'),
            new TypeToken(TypeToken::TOKEN_BRACE_CLOSE, '}'),
            new TypeToken(TypeToken::TOKEN_BRACE_CLOSE, '}'),
        );
        $data['Nested hash'] = array($expected, $source);

        $source = '1, 1.0';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_INTEGER, '1'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_FLOAT, '1.0'),
        );
        $data['Treatment of numbers'] = array($expected, $source);

        $source = 'true, TRUE, True, false, null';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_BOOLEAN_TRUE, 'true'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_BOOLEAN_TRUE, 'TRUE'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_BOOLEAN_TRUE, 'True'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_BOOLEAN_FALSE, 'false'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_NULL, 'null'),
        );
        $data['Treatment of booleans and nulls'] = array($expected, $source);

        $source = '+= ++ || ?> %> {$ ${ => >= <> <= -> <?php <' . '? <% <?= <%= |= :: << <<= >> >>= <<<';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_PLUS, '+'),
            new TypeToken(TypeToken::TOKEN_STRING, '='),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_PLUS, '+'),
            new TypeToken(TypeToken::TOKEN_PLUS, '+'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_PIPE, '|'),
            new TypeToken(TypeToken::TOKEN_PIPE, '|'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, '?'),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, '%'),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_BRACE_OPEN, '{'),
            new TypeToken(TypeToken::TOKEN_STRING, '$'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, '$'),
            new TypeToken(TypeToken::TOKEN_BRACE_OPEN, '{'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, '='),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
            new TypeToken(TypeToken::TOKEN_STRING, '='),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_STRING, '='),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, '-'),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_STRING, '?php'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_STRING, '?'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_STRING, '%'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_STRING, '?='),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_STRING, '%='),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_PIPE, '|'),
            new TypeToken(TypeToken::TOKEN_STRING, '='),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_STRING, '='),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
            new TypeToken(TypeToken::TOKEN_STRING, '='),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
        );
        $data['Treatment of unsupported tokens containing supported tokens'] = array($expected, $source);

        $source = '"foo $bar $baz[0] $qux->doom {$great} ${great} {$square->width} {$arr[\'key\']} {$arr[4][3]} {$arr[\'foo\'][3]} {$obj->values[3]->name} {${$name}} {${getName()}} {${$object->getName()}} \\\\\\""';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_STRING_QUOTED, $source),
        );
        $data['Treatment of quoted strings containing variables etc.'] = array($expected, $source);

        $source = 'type<subType, subType<subSubType, subSubType>>';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_STRING, 'type'),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_STRING, 'subType'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'subType'),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_STRING, 'subSubType'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'subSubType'),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
        );
        $data['Nested sub-types'] = array($expected, $source);

        $source = 'type<subType, subType>{foo: bar}';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_STRING, 'type'),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_STRING, 'subType'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'subType'),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
            new TypeToken(TypeToken::TOKEN_BRACE_OPEN, '{'),
            new TypeToken(TypeToken::TOKEN_STRING, 'foo'),
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'bar'),
            new TypeToken(TypeToken::TOKEN_BRACE_CLOSE, '}'),
        );
        $data['Type with attributes and subtypes'] = array($expected, $source);

        $source = 'Foo\Bar\Baz';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_STRING, 'Foo\Bar\Baz'),
        );
        $data['Namespaced type name'] = array($expected, $source);

        $source = ':Foo';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_STRING, 'Foo'),
        );
        $data['Basic extension type'] = array($expected, $source);

        $source = ':Foo\Bar\Baz';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_STRING, 'Foo\Bar\Baz'),
        );
        $data['Namespaced extension type'] = array($expected, $source);

        $source = ':Foo\Bar\Baz{foo: bar}';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_STRING, 'Foo\Bar\Baz'),
            new TypeToken(TypeToken::TOKEN_BRACE_OPEN, '{'),
            new TypeToken(TypeToken::TOKEN_STRING, 'foo'),
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'bar'),
            new TypeToken(TypeToken::TOKEN_BRACE_CLOSE, '}'),
        );
        $data['Namespaced extension type with attributes'] = array($expected, $source);

        $source = ':Foo\Bar\Baz<subType, subType>{foo: bar}';
        $expected = array(
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_STRING, 'Foo\Bar\Baz'),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_STRING, 'subType'),
            new TypeToken(TypeToken::TOKEN_COMMA, ','),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'subType'),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
            new TypeToken(TypeToken::TOKEN_BRACE_OPEN, '{'),
            new TypeToken(TypeToken::TOKEN_STRING, 'foo'),
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'bar'),
            new TypeToken(TypeToken::TOKEN_BRACE_CLOSE, '}'),
        );
        $data['Namespaced extension type with subtypes and attributes'] = array($expected, $source);

        return $data;
    }

    /**
     * @dataProvider tokenData
     */
    public function testTokens(array $expected, $source)
    {
        $this->assertEquals($expected, $this->lexer->tokens($source, 0));
    }

    public function testTokensWithNonZeroOffset()
    {
        $source = '@param type<subType>{foo: bar}';
        $offset = 7;
        $expected = array(
            new TypeToken(TypeToken::TOKEN_STRING, 'type'),
            new TypeToken(TypeToken::TOKEN_LESS_THAN, '<'),
            new TypeToken(TypeToken::TOKEN_STRING, 'subType'),
            new TypeToken(TypeToken::TOKEN_GREATER_THAN, '>'),
            new TypeToken(TypeToken::TOKEN_BRACE_OPEN, '{'),
            new TypeToken(TypeToken::TOKEN_STRING, 'foo'),
            new TypeToken(TypeToken::TOKEN_COLON, ':'),
            new TypeToken(TypeToken::TOKEN_WHITESPACE, ' '),
            new TypeToken(TypeToken::TOKEN_STRING, 'bar'),
            new TypeToken(TypeToken::TOKEN_BRACE_CLOSE, '}'),
        );

        $this->assertEquals($expected, $this->lexer->tokens($source, $offset));
    }
}
