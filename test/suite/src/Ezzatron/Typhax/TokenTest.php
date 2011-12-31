<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhax;

class TokenTest extends \Ezzatron\Typhax\Test\TestCase
{
  /**
   * @covers Ezzatron\Typhax\Token::fromArray
   * @group lexer
   * @group core
   */
  public function testFromArray()
  {
    $token = array(T_STRING, 'foo', 0);
    $expected = new Token(Token::TOKEN_STRING, 'foo');

    $this->assertEquals($expected, Token::fromArray($token));

    $token = array(T_STRING, 'bar', 666);
    $expected = new Token(Token::TOKEN_STRING, 'bar');

    $this->assertEquals($expected, Token::fromArray($token));
  }

  /**
   * @covers Ezzatron\Typhax\Token::fromCharacter
   * @group lexer
   * @group core
   */
  public function testFromCharacter()
  {
    $token = '&';
    $expected = new Token(Token::TOKEN_AND, '&');

    $this->assertEquals($expected, Token::fromCharacter($token));

    $token = '|';
    $expected = new Token(Token::TOKEN_OR, '|');

    $this->assertEquals($expected, Token::fromCharacter($token));
  }

  /**
   * @covers Ezzatron\Typhax\Token::__construct
   * @covers Ezzatron\Typhax\Token::type
   * @covers Ezzatron\Typhax\Token::content
   * @covers Ezzatron\Typhax\Token::string
   * @covers Ezzatron\Typhax\Token::__toString
   * @group lexer
   * @group core
   */
  public function testToken()
  {
    $token = new Token(Token::TOKEN_STRING, 'foo');

    $this->assertSame(Token::TOKEN_STRING, $token->type());
    $this->assertSame('foo', $token->content());
    $this->assertSame('foo', $token->string());
    $this->assertSame('foo', (string)$token);
  }

  /**
   * @covers Ezzatron\Typhax\Token::append
   * @group lexer
   * @group core
   */
  public function testAppend()
  {
    $token = new Token(Token::TOKEN_STRING, 'foo');

    $this->assertSame('foo', $token->content());

    $token->append('bar');

    $this->assertSame('foobar', $token->content());

    $token->append('baz');

    $this->assertSame('foobarbaz', $token->content());
  }
  
  /**
   * @return array
   */
  public function nameData()
  {
    return array(
      array('AND', Token::TOKEN_AND),
      array('OR', Token::TOKEN_OR),
      array('SEPARATOR', Token::TOKEN_SEPARATOR),
      array('STRING', Token::TOKEN_STRING),
      array('SUBTYPE_CLOSE', Token::TOKEN_SUBTYPE_CLOSE),
      array('SUBTYPE_OPEN', Token::TOKEN_SUBTYPE_OPEN),
      array('WHITESPACE', Token::TOKEN_WHITESPACE),

      array(null, T_FUNCTION),
      array(null, '-'),
    );
  }

  /**
   * @covers Ezzatron\Typhax\Token::name
   * @covers Ezzatron\Typhax\Token::supported
   * @covers Ezzatron\Typhax\Token::types
   * @dataProvider nameData
   * @group lexer
   * @group core
   */
  public function testNameAndSupported($expected, $type)
  {
    $reflector = new \ReflectionClass(__NAMESPACE__.'\Token');
    $property = $reflector->getProperty('types');
    $property->setAccessible(true);
    $property->setValue(null, null);

    $token = new Token($type, 'foo');

    $this->assertSame($expected, $token->name());
    $this->assertSame(null !== $expected, $token->supported());
  }
}