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

class TypeTest extends \Ezzatron\Typhax\Test\TestCase
{
  /**
   * @covers Ezzatron\Typhax\Type::__construct
   * @covers Ezzatron\Typhax\Type::name
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
   * @covers Ezzatron\Typhax\Type::__construct
   * @group ast
   * @group core
   */
  public function testTypeFailure()
  {
    $this->setExpectedException('\InvalidArgumentException');
    $type = new Type(null);
  }

  /**
   * @covers Ezzatron\Typhax\Type::setAttribute
   * @covers Ezzatron\Typhax\Type::attributes
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
   * @covers Ezzatron\Typhax\Type::setAttribute
   * @group ast
   * @group core
   */
  public function testSetAttributeFailure()
  {
    $type = new Type('foo');

    $this->setExpectedException('\InvalidArgumentException');
    $type->setAttribute(null, 'bar');
  }

  /**
   * @covers Ezzatron\Typhax\Type::addSubType
   * @covers Ezzatron\Typhax\Type::subTypes
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

    $subTypeBaz = new Type('baz');
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
