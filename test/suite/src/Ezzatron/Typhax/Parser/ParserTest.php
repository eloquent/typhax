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
use Ezzatron\Typhax\AST\Type;

class ParserTest extends \Ezzatron\Typhax\Test\TestCase
{
  /**
   * @return array
   */
  public function parserData()
  {
    $data = array();

    // #0: Basic example
    $source = 'type';
    $expected = new Type('type');
    $data[] = array($expected, $source);

    return $data;
  }

  /**
   * @covers Ezzatron\Typhax\Parser\Parser
   * @dataProvider parserData
   * @group parser
   * @group core
   */
  public function testParser(Type $expected, $source)
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
