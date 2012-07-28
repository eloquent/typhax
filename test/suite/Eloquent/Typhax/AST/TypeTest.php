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

class TypeTest extends \Eloquent\Typhax\Test\TestCase
{
  /**
   * @covers Eloquent\Typhax\AST\Type::__construct
   * @covers Eloquent\Typhax\AST\Type::name
   * @group ast
   * @group core
   */
  public function testType()
  {
    $type = new Type('foo');

    $this->assertSame('foo', $type->name());

    $type = new Type('bar');

    $this->assertSame('bar', $type->name());
  }

  /**
   * @covers Eloquent\Typhax\AST\Type::setAttribute
   * @covers Eloquent\Typhax\AST\Type::attributes
   * @group ast
   * @group core
   */
  public function testAttributes()
  {
    $type = new Type('foo');

    $this->assertSame(array(), $type->attributes());

    $type->setAttribute('bar', 'baz');

    $this->assertSame(array(
      'bar' => 'baz',
    ), $type->attributes());

    $type->setAttribute('qux', 'doom');
    $type->setAttribute('splat', 'buzz');

    $this->assertSame(array(
      'bar' => 'baz',
      'qux' => 'doom',
      'splat' => 'buzz',
    ), $type->attributes());
  }

  /**
   * @covers Eloquent\Typhax\AST\Type::addSubType
   * @covers Eloquent\Typhax\AST\Type::subTypes
   * @group ast
   * @group core
   */
  public function testSubTypes()
  {
    $type = new Type('foo');

    $this->assertSame(array(), $type->subTypes());

    $subTypeBar = new Type('bar');
    $type->addSubType($subTypeBar);

    $this->assertSame(array(
      $subTypeBar,
    ), $type->subTypes());

    $subTypeBaz = new Composite('baz');
    $type->addSubType($subTypeBaz);
    $subTypeQux = new Type('qux');
    $type->addSubType($subTypeQux);

    $this->assertSame(array(
      $subTypeBar,
      $subTypeBaz,
      $subTypeQux,
    ), $type->subTypes());
  }
}