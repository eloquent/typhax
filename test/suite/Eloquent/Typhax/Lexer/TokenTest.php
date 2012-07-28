<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Lexer;

class TokenTest extends \PHPUnit_Framework_TestCase
{
    public function tokenData()
    {
        $data = array();

        // #0: Array token
        $token = array(T_STRING, 'foo', 0);
        $expected = new Token(Token::TOKEN_STRING, 'foo');
        $data[] = array($expected, $token);

        // #1: Array token
        $token = array(T_STRING, 'bar', 666);
        $expected = new Token(Token::TOKEN_STRING, 'bar');
        $data[] = array($expected, $token);

        // #2: Character token
        $token = '&';
        $expected = new Token(Token::TOKEN_AND, '&');
        $data[] = array($expected, $token);

        // #3: Character token
        $token = '|';
        $expected = new Token(Token::TOKEN_PIPE, '|');
        $data[] = array($expected, $token);

        return $data;
    }

    /**
     * @dataProvider tokenData
     */
    public function testFromToken(Token $expected, $token)
    {
        $this->assertEquals($expected, Token::fromToken($token));
    }

    public function testToken()
    {
        $token = new Token(Token::TOKEN_STRING, 'foo');

        $this->assertSame(Token::TOKEN_STRING, $token->type());
        $this->assertSame('foo', $token->content());
        $this->assertSame('foo', $token->string());
        $this->assertSame('foo', (string)$token);
    }

    public function testAppend()
    {
        $token = new Token(Token::TOKEN_STRING, 'foo');

        $this->assertSame('foo', $token->content());

        $token->append('bar');

        $this->assertSame('foobar', $token->content());

        $token->append('baz');

        $this->assertSame('foobarbaz', $token->content());
    }

    public function nameData()
    {
        return array(
            array('AND', Token::TOKEN_AND),
            array('BOOLEAN_FALSE', Token::TOKEN_BOOLEAN_FALSE),
            array('BOOLEAN_TRUE', Token::TOKEN_BOOLEAN_TRUE),
            array('BRACE_CLOSE', Token::TOKEN_BRACE_CLOSE),
            array('BRACE_OPEN', Token::TOKEN_BRACE_OPEN),
            array('COLON', Token::TOKEN_COLON),
            array('COMMA', Token::TOKEN_COMMA),
            array('END', Token::TOKEN_END),
            array('FLOAT', Token::TOKEN_FLOAT),
            array('GREATER_THAN', Token::TOKEN_GREATER_THAN),
            array('INTEGER', Token::TOKEN_INTEGER),
            array('LESS_THAN', Token::TOKEN_LESS_THAN),
            array('NULL', Token::TOKEN_NULL),
            array('PARENTHESIS_CLOSE', Token::TOKEN_PARENTHESIS_CLOSE),
            array('PARENTHESIS_OPEN', Token::TOKEN_PARENTHESIS_OPEN),
            array('PIPE', Token::TOKEN_PIPE),
            array('SQUARE_BRACKET_CLOSE', Token::TOKEN_SQUARE_BRACKET_CLOSE),
            array('SQUARE_BRACKET_OPEN', Token::TOKEN_SQUARE_BRACKET_OPEN),
            array('STRING', Token::TOKEN_STRING),
            array('STRING_QUOTED', Token::TOKEN_STRING_QUOTED),
            array('WHITESPACE', Token::TOKEN_WHITESPACE),

            array(null, T_FUNCTION),
            array(null, '-'),
        );
    }

    /**
     * @dataProvider nameData
     */
    public function testNameAndSupported($expected, $type)
    {
        $this->assertSame($expected, Token::nameByType($type));

        $reflector = new \ReflectionClass(__NAMESPACE__.'\Token');
        $property = $reflector->getProperty('types');
        $property->setAccessible(true);
        $property->setValue(null, null);

        $token = new Token($type, 'foo');

        $this->assertSame($expected, $token->name());
        $this->assertSame(null !== $expected, $token->supported());
    }
}
