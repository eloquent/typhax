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

class LexerTest extends \Ezzatron\Typhax\Test\TestCase
{
  /**
   * @return array
   */
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
      new Token(Token::TOKEN_SUBTYPE_OPEN, '<'),
      new Token(Token::TOKEN_STRING, 'subType'),
      new Token(Token::TOKEN_SUBTYPE_CLOSE, '>'),
    );
    $data[] = array($expected, $source);

    // #2: Type with key type and sub-type
    $source = 'type<keyType, subType>';
    $expected = array(
      new Token(Token::TOKEN_STRING, 'type'),
      new Token(Token::TOKEN_SUBTYPE_OPEN, '<'),
      new Token(Token::TOKEN_STRING, 'keyType'),
      new Token(Token::TOKEN_SEPARATOR, ','),
      new Token(Token::TOKEN_WHITESPACE, ' '),
      new Token(Token::TOKEN_STRING, 'subType'),
      new Token(Token::TOKEN_SUBTYPE_CLOSE, '>'),
    );
    $data[] = array($expected, $source);

    // #3: Composite OR type
    $source = 'type|type';
    $expected = array(
      new Token(Token::TOKEN_STRING, 'type'),
      new Token(Token::TOKEN_OR, '|'),
      new Token(Token::TOKEN_STRING, 'type'),
    );
    $data[] = array($expected, $source);

    // #4: Composite AND type
    $source = 'type&type';
    $expected = array(
      new Token(Token::TOKEN_STRING, 'type'),
      new Token(Token::TOKEN_AND, '&'),
      new Token(Token::TOKEN_STRING, 'type'),
    );
    $data[] = array($expected, $source);

    // #5: Treatment of unsupported tokens as strings
    $source = 'type-type';
    $expected = array(
      new Token(Token::TOKEN_STRING, 'type-type'),
    );
    $data[] = array($expected, $source);

    return $data;
  }

  /**
   * @covers Ezzatron\Typhax\Lexer::tokens
   * @dataProvider tokenData
   * @group lexer
   * @group core
   */
  public function testTokens(array $expected, $source)
  {
    $lexer = new Lexer;

    $this->assertEquals($expected, $lexer->tokens($source));
  }
}