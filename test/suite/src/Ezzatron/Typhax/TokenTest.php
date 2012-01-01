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
   * @return array
   */
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
    $expected = new Token(Token::TOKEN_OR, '|');
    $data[] = array($expected, $token);
    
    return $data;
  }

  /**
   * @covers Ezzatron\Typhax\Token::fromToken
   * @covers Ezzatron\Typhax\Token::fromArray
   * @covers Ezzatron\Typhax\Token::fromCharacter
   * @dataProvider tokenData
   * @group lexer
   * @group core
   */
  public function testFromToken(Token $expected, $token)
  {
    $this->assertEquals($expected, Token::fromToken($token));
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
      array('ARRAY_CLOSE', Token::TOKEN_ARRAY_CLOSE),
      array('ARRAY_OPEN', Token::TOKEN_ARRAY_OPEN),
      array('ASSIGNMENT', Token::TOKEN_ASSIGNMENT),
      array('ATTRIBUTES_CLOSE', Token::TOKEN_ATTRIBUTES_CLOSE),
      array('ATTRIBUTES_OPEN', Token::TOKEN_ATTRIBUTES_OPEN),
      array('BOOLEAN_FALSE', Token::TOKEN_BOOLEAN_FALSE),
      array('BOOLEAN_TRUE', Token::TOKEN_BOOLEAN_TRUE),
      array('FLOAT', Token::TOKEN_FLOAT),
      array('HASH_CLOSE', Token::TOKEN_HASH_CLOSE),
      array('HASH_OPEN', Token::TOKEN_HASH_OPEN),
      array('INTEGER', Token::TOKEN_INTEGER),
      array('NULL', Token::TOKEN_NULL),
      array('OR', Token::TOKEN_OR),
      array('SEPARATOR', Token::TOKEN_SEPARATOR),
      array('STRING', Token::TOKEN_STRING),
      array('STRING_QUOTED', Token::TOKEN_STRING_QUOTED),
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
