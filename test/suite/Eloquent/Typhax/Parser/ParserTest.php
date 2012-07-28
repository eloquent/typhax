<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Parser;

use Eloquent\Typhax\AST\Composite;
use Eloquent\Typhax\AST\Node;
use Eloquent\Typhax\AST\Type;
use Eloquent\Typhax\Lexer\Lexer;
use Eloquent\Typhax\Lexer\Token;

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

    // #7: Empty attributes
    $source = 'foo()';
    $expected = new Type('foo');
    $data[] = array($expected, $source);

    // #8: Basic attributes
    $source = 'foo(bar:"baz",\'qux\':666,doom:.666,splat:null,pip:true,pop:false)';
    $expected = new Type('foo');
    $expected->setAttribute('bar', 'baz');
    $expected->setAttribute('qux', 666);
    $expected->setAttribute('doom', .666);
    $expected->setAttribute('splat', null);
    $expected->setAttribute('pip', true);
    $expected->setAttribute('pop', false);
    $data[] = array($expected, $source);

    // #9: Nested hash attribute
    $source = 'foo(bar:{baz:{qux:doom}})';
    $expected = new Type('foo');
    $expected->setAttribute('bar', array(
      'baz' => array(
        'qux' => 'doom',
      ),
    ));
    $data[] = array($expected, $source);

    // #10: Nested array attribute
    $source = 'foo(bar:[baz,[qux,doom]])';
    $expected = new Type('foo');
    $expected->setAttribute('bar', array(
      'baz',
      array(
        'qux',
        'doom',
      ),
    ));
    $data[] = array($expected, $source);

    // #11: Empty hash and array
    $source = 'foo(bar:{},baz:[])';
    $expected = new Type('foo');
    $expected->setAttribute('bar', array());
    $expected->setAttribute('baz', array());
    $data[] = array($expected, $source);

    // #12: Empty subtypes
    $source = 'foo<>';
    $expected = new Type('foo');
    $data[] = array($expected, $source);

    // #13: Basic subtypes
    $source = 'foo<bar,baz>';
    $expected = new Type('foo');
    $expected->addSubType(new Type('bar'));
    $expected->addSubType(new Type('baz'));
    $data[] = array($expected, $source);

    // #14: Nested subtypes
    $source = 'foo<bar,baz<qux,doom>>';
    $expectedBaz = new Type('baz');
    $expectedBaz->addSubType(new Type('qux'));
    $expectedBaz->addSubType(new Type('doom'));
    $expected = new Type('foo');
    $expected->addSubType(new Type('bar'));
    $expected->addSubType($expectedBaz);
    $data[] = array($expected, $source);

    // #15: Mixed subtypes and attributes
    $source = 'foo<bar,baz>(qux:doom)';
    $expected = new Type('foo');
    $expected->addSubType(new Type('bar'));
    $expected->addSubType(new Type('baz'));
    $expected->setAttribute('qux', 'doom');
    $data[] = array($expected, $source);

    return $data;
  }

  /**
   * @dataProvider parserData
   */
  public function testParser(Node $expected, $source)
  {
    $tokens = $this->_lexer->tokens($source);
    $parser = new Parser;
    $actual = $parser->parseNode($tokens);

    $this->assertEquals($expected, $actual);

    if ($expected instanceof Type)
    {
      $this->assertSame($expected->attributes(), $actual->attributes());
    }
  }

  public function parseHashData()
  {
    $data = array();

    // #0: Empty hash
    $source = '{}';
    $expected = array();
    $data[] = array($expected, $source);

    // #1: Basic hash
    $source = '{bar:"baz",\'qux\':666,doom:.666,splat:null,pip:true,pop:false}';
    $expected = array(
      'bar' => 'baz',
      'qux' => 666,
      'doom' => .666,
      'splat' => null,
      'pip' => true,
      'pop' => false,
    );
    $data[] = array($expected, $source);

    // #2: Nested hashes and arrays
    $source = '{bar:{baz:{qux:doom}},splat:[pip,[pop,pep]]}';
    $expected = array(
      'bar' => array(
        'baz' => array(
          'qux' => 'doom',
        ),
      ),
      'splat' => array(
        'pip',
        array('pop', 'pep'),
      ),
    );
    $data[] = array($expected, $source);

    return $data;
  }

  /**
   * @dataProvider parseHashData
   */
  public function testParseHash(array $expected, $source)
  {
    $tokens = $this->_lexer->tokens($source);
    $parser = new Parser;
    $actual = $parser->parseHash($tokens);

    $this->assertSame($expected, $actual);
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

    return $data;
  }

  /**
   * @dataProvider parserFailureData
   */
  public function testParserFailure($expectedClass, $expectedMessage, $source)
  {
    $tokens = $this->_lexer->tokens($source);
    $parser = new Parser;

    $this->setExpectedException($expectedClass, $expectedMessage);
    $parser->parseNode($tokens);
  }
}
