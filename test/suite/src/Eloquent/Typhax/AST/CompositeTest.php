<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\AST;

class CompositeTest extends \Eloquent\Typhax\Test\TestCase
{
  /**
   * @covers Eloquent\Typhax\AST\Composite::__construct
   * @covers Eloquent\Typhax\AST\Composite::separator
   * @group ast
   * @group core
   */
  public function testComposite()
  {
    $composite = new Composite('foo');

    $this->assertSame('foo', $composite->separator());

    $composite = new Composite('bar');

    $this->assertSame('bar', $composite->separator());
  }

  /**
   * @covers Eloquent\Typhax\AST\Composite::addType
   * @covers Eloquent\Typhax\AST\Composite::types
   * @group ast
   * @group core
   */
  public function testTypes()
  {
    $composite = new Composite('foo');

    $this->assertSame(array(), $composite->types());

    $typeBar = new Type('bar');
    $composite->addType($typeBar);

    $this->assertSame(array(
      $typeBar,
    ), $composite->types());

    $typeBaz = new Composite('baz');
    $composite->addType($typeBaz);
    $typeQux = new Type('qux');
    $composite->addType($typeQux);

    $this->assertSame(array(
      $typeBar,
      $typeBaz,
      $typeQux,
    ), $composite->types());
  }
}