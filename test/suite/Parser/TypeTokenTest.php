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
use ReflectionClass;

/**
 * @covers \Eloquent\Typhax\Parser\TypeToken
 */
class TypeTokenTest extends PHPUnit_Framework_TestCase
{
    public function tokenData()
    {
        $data = array();

        $token = array(T_STRING, 'foo');
        $expected = new TypeToken(TypeToken::TOKEN_STRING, 'foo');
        $data['Array token'] = array($expected, $token);

        $token = '+';
        $expected = new TypeToken(TypeToken::TOKEN_PLUS, '+');
        $data['Character token'] = array($expected, $token);

        return $data;
    }

    /**
     * @dataProvider tokenData
     */
    public function testFromToken($expected, $token)
    {
        $this->assertEquals($expected, TypeToken::fromToken($token));
    }

    public function testToken()
    {
        $token = new TypeToken(TypeToken::TOKEN_STRING, 'foo');

        $this->assertSame(TypeToken::TOKEN_STRING, $token->type());
        $this->assertSame('foo', $token->content());
    }

    public function testAppend()
    {
        $token = new TypeToken(TypeToken::TOKEN_STRING, 'foo');

        $this->assertSame('foo', $token->content());

        $token->append('bar');

        $this->assertSame('foobar', $token->content());

        $token->append('baz');

        $this->assertSame('foobarbaz', $token->content());
    }

    public function nameData()
    {
        return array(
            array('BOOLEAN_FALSE', TypeToken::TOKEN_BOOLEAN_FALSE),
            array('BOOLEAN_TRUE', TypeToken::TOKEN_BOOLEAN_TRUE),
            array('BRACE_CLOSE', TypeToken::TOKEN_BRACE_CLOSE),
            array('BRACE_OPEN', TypeToken::TOKEN_BRACE_OPEN),
            array('COLON', TypeToken::TOKEN_COLON),
            array('COMMA', TypeToken::TOKEN_COMMA),
            array('FLOAT', TypeToken::TOKEN_FLOAT),
            array('GREATER_THAN', TypeToken::TOKEN_GREATER_THAN),
            array('INTEGER', TypeToken::TOKEN_INTEGER),
            array('LESS_THAN', TypeToken::TOKEN_LESS_THAN),
            array('NULL', TypeToken::TOKEN_NULL),
            array('PIPE', TypeToken::TOKEN_PIPE),
            array('PLUS', TypeToken::TOKEN_PLUS),
            array('SQUARE_BRACKET_CLOSE', TypeToken::TOKEN_SQUARE_BRACKET_CLOSE),
            array('SQUARE_BRACKET_OPEN', TypeToken::TOKEN_SQUARE_BRACKET_OPEN),
            array('STRING', TypeToken::TOKEN_STRING),
            array('STRING_QUOTED', TypeToken::TOKEN_STRING_QUOTED),
            array('TYPE_NAME', TypeToken::TOKEN_TYPE_NAME),
            array('WHITESPACE', TypeToken::TOKEN_WHITESPACE),

            array(null, T_FUNCTION),
            array(null, '-'),
        );
    }

    /**
     * @dataProvider nameData
     */
    public function testNameAndSupported($expected, $type)
    {
        $this->assertSame($expected, TypeToken::nameByType($type));

        $reflector = new ReflectionClass(__NAMESPACE__ . '\TypeToken');
        $property = $reflector->getProperty('types');
        $property->setAccessible(true);
        $property->setValue(null, null);

        $token = new TypeToken($type, 'foo');

        $this->assertSame($expected, $token->name());
        $this->assertSame(null !== $expected, $token->isSupported());
    }

    public function testNamesByType()
    {
        $this->assertSame(
            array('NULL', 'PLUS'),
            TypeToken::namesByType(array(TypeToken::TOKEN_NULL, TypeToken::TOKEN_PLUS))
        );
    }
}
