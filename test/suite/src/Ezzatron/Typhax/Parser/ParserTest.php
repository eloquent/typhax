<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhax\Parser;

use Ezzatron\Typhax\AST\Composite;
use Ezzatron\Typhax\AST\Node;
use Ezzatron\Typhax\AST\Type;
use Ezzatron\Typhax\Lexer\Token;

class ParserTest extends \Ezzatron\Typhax\Test\TestCase
{
  /**
   * @return array
   */
  public function parserData()
  {
    $data = array();

    // #0: Basic example
    $source = 'foo';
    $expected = new Type('foo');
    $data[] = array($expected, $source);

    // #1: Basic composite OR
    $source = 'foo|bar';
    $expected = new Composite(Token::TOKEN_PIPE);
    $expected->addType(new Type('foo'));
    $expected->addType(new Type('bar'));
    $data[] = array($expected, $source);

    // #2: Basic composite AND
    $source = 'foo&bar';
    $expected = new Composite(Token::TOKEN_AND);
    $expected->addType(new Type('foo'));
    $expected->addType(new Type('bar'));
    $data[] = array($expected, $source);

    // #3: Composite precedence
    $source = 'foo|bar&baz';
    $expectedBarBaz = new Composite(Token::TOKEN_AND);
    $expectedBarBaz->addType(new Type('bar'));
    $expectedBarBaz->addType(new Type('baz'));
    $expected = new Composite(Token::TOKEN_PIPE);
    $expected->addType(new Type('foo'));
    $expected->addType($expectedBarBaz);
    $data[] = array($expected, $source);

    // #4: Composite precedence
    $source = 'foo&bar|baz';
    $expectedFooBar = new Composite(Token::TOKEN_AND);
    $expectedFooBar->addType(new Type('foo'));
    $expectedFooBar->addType(new Type('bar'));
    $expected = new Composite(Token::TOKEN_PIPE);
    $expected->addType($expectedFooBar);
    $expected->addType(new Type('baz'));
    $data[] = array($expected, $source);

    // #5: Composite precedence
    $source = 'foo|bar&baz|qux';
    $expectedBarBaz = new Composite(Token::TOKEN_AND);
    $expectedBarBaz->addType(new Type('bar'));
    $expectedBarBaz->addType(new Type('baz'));
    $expected = new Composite(Token::TOKEN_PIPE);
    $expected->addType(new Type('foo'));
    $expected->addType($expectedBarBaz);
    $expected->addType(new Type('qux'));
    $data[] = array($expected, $source);

    // #6: Composite precedence with multiple sub-composites
    $source = 'foo&bar|baz&qux|doom&splat';
    $expectedFooBar = new Composite(Token::TOKEN_AND);
    $expectedFooBar->addType(new Type('foo'));
    $expectedFooBar->addType(new Type('bar'));
    $expectedBazQux = new Composite(Token::TOKEN_AND);
    $expectedBazQux->addType(new Type('baz'));
    $expectedBazQux->addType(new Type('qux'));
    $expectedDoomSplat = new Composite(Token::TOKEN_AND);
    $expectedDoomSplat->addType(new Type('doom'));
    $expectedDoomSplat->addType(new Type('splat'));
    $expected = new Composite(Token::TOKEN_PIPE);
    $expected->addType($expectedFooBar);
    $expected->addType($expectedBazQux);
    $expected->addType($expectedDoomSplat);
    $data[] = array($expected, $source);

    return $data;
  }

  /**
   * @covers Ezzatron\Typhax\Parser\Parser
   * @dataProvider parserData
   * @group parser
   * @group core
   */
  public function testParser(Node $expected, $source)
  {
    $parser = new Parser;

    $this->assertEquals($expected, $parser->parse($source));
  }

  /**
   * @return array
   */
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
    $expectedMessage = 'Unexpected BRACE_OPEN at position 5. Expected one of PARENTHESIS_OPEN, LESS_THAN, PIPE, AND, END.';
    $data[] = array($expectedClass, $expectedMessage, $source);

    return $data;
  }

  /**
   * @covers Ezzatron\Typhax\Parser\Parser
   * @dataProvider parserFailureData
   * @group parser
   * @group core
   */
  public function testParserFailure($expectedClass, $expectedMessage, $source)
  {
    $parser = new Parser;

    $this->setExpectedException($expectedClass, $expectedMessage);
    $parser->parse($source);
  }
}
