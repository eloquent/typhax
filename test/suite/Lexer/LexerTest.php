<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Lexer;

use PHPUnit_Framework_TestCase;

class LexerTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->lexer = new Lexer;
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
    public function testIntrinsicTypeNames($typeName)
    {
        $expected = array(new Token(Token::TOKEN_TYPE_NAME, $typeName));

        $this->assertEquals($expected, $this->lexer->tokens($typeName));
    }

    public function tokenData()
    {
        return array(
            'Basic type' => array(
                'type',
                array(
                    new Token(Token::TOKEN_STRING, 'type'),
                ),
            ),
            'Type with sub-type' => array(
                'type<subType>',
                array(
                    new Token(Token::TOKEN_STRING, 'type'),
                    new Token(Token::TOKEN_LESS_THAN, '<'),
                    new Token(Token::TOKEN_STRING, 'subType'),
                    new Token(Token::TOKEN_GREATER_THAN, '>'),
                ),
            ),
            'Type with multiple sub-types' => array(
                'type<subType, subType, subType>',
                array(
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
                ),
            ),
            'Composite OR type' => array(
                'type|type',
                array(
                    new Token(Token::TOKEN_STRING, 'type'),
                    new Token(Token::TOKEN_PIPE, '|'),
                    new Token(Token::TOKEN_STRING, 'type'),
                ),
            ),
            'Composite AND type' => array(
                'type+type',
                array(
                    new Token(Token::TOKEN_STRING, 'type'),
                    new Token(Token::TOKEN_PLUS, '+'),
                    new Token(Token::TOKEN_STRING, 'type'),
                ),
            ),
            'Treatment of unsupported tokens as strings' => array(
                'type-type',
                array(
                    new Token(Token::TOKEN_STRING, 'type-type'),
                ),
            ),
            'Dynamic type with basic attributes and quoted strings' => array(
                'type{foo: bar, \'baz\': "qux"}',
                array(
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
                ),
            ),
            'Dynamic type with array attributes' => array(
                'type{foo: [bar, baz], qux: {doom: splat, pip: pop}}',
                array(
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
                ),
            ),
            'Nested array' => array(
                '[foo, [bar, baz]]',
                array(
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
                ),
            ),
            'Nested hash' => array(
                '{foo: bar, {bar: baz, qux: doom}}',
                array(
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
                ),
            ),
            'Treatment of numbers' => array(
                '1, 1.0',
                array(
                    new Token(Token::TOKEN_INTEGER, '1'),
                    new Token(Token::TOKEN_COMMA, ','),
                    new Token(Token::TOKEN_WHITESPACE, ' '),
                    new Token(Token::TOKEN_FLOAT, '1.0'),
                ),
            ),
            'Treatment of booleans and nulls' => array(
                'true, TRUE, True, false, null',
                array(
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
                ),
            ),
            'Treatment of unsupported tokens containing supported tokens' => array(
                '+= ++ || ?> %> {$ ${ => >= <> <= -> <?php <' . '? <% <?= <%= |= :: << <<= >> >>= <<<',
                array(
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
                ),
            ),
            'Treatment of quoted strings containing variables etc.' => array(
                '"foo $bar $baz[0] $qux->doom {$great} ${great} {$square->width} {$arr[\'key\']} ' .
                '{$arr[4][3]} {$arr[\'foo\'][3]} {$obj->values[3]->name} {${$name}} {${getName()}} ' .
                '{${$object->getName()}} \\\\\\""',
                array(
                    new Token(
                        Token::TOKEN_STRING_QUOTED,
                        '"foo $bar $baz[0] $qux->doom {$great} ${great} {$square->width} {$arr[\'key\']} ' .
                        '{$arr[4][3]} {$arr[\'foo\'][3]} {$obj->values[3]->name} {${$name}} {${getName()}} ' .
                        '{${$object->getName()}} \\\\\\""'
                    ),
                ),
            ),
            'Nested sub-types' => array(
                'type<subType, subType<subSubType, subSubType>>',
                array(
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
                ),
            ),
            'Type with attributes and subtypes' => array(
                'type<subType, subType>{foo: bar}',
                array(
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
                ),
            ),
            'Namespaced type name' => array(
                'Foo\Bar\Baz',
                array(
                    new Token(Token::TOKEN_STRING, 'Foo\Bar\Baz'),
                ),
            ),
            'Basic extension type' => array(
                ':Foo',
                array(
                    new Token(Token::TOKEN_COLON, ':'),
                    new Token(Token::TOKEN_STRING, 'Foo'),
                ),
            ),
            'Namespaced extension type' => array(
                ':Foo\Bar\Baz',
                array(
                    new Token(Token::TOKEN_COLON, ':'),
                    new Token(Token::TOKEN_STRING, 'Foo\Bar\Baz'),
                ),
            ),
            'Namespaced extension type with attributes' => array(
                ':Foo\Bar\Baz{foo: bar}',
                array(
                    new Token(Token::TOKEN_COLON, ':'),
                    new Token(Token::TOKEN_STRING, 'Foo\Bar\Baz'),
                    new Token(Token::TOKEN_BRACE_OPEN, '{'),
                    new Token(Token::TOKEN_STRING, 'foo'),
                    new Token(Token::TOKEN_COLON, ':'),
                    new Token(Token::TOKEN_WHITESPACE, ' '),
                    new Token(Token::TOKEN_STRING, 'bar'),
                    new Token(Token::TOKEN_BRACE_CLOSE, '}'),
                ),
            ),
            'Namespaced extension type with subtypes and attributes' => array(
                ':Foo\Bar\Baz<subType, subType>{foo: bar}',
                array(
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
                ),
            ),
        );
    }

    /**
     * @dataProvider tokenData
     */
    public function testTokens($source, $expected)
    {
        $this->assertEquals($expected, $this->lexer->tokens($source));
    }
}
