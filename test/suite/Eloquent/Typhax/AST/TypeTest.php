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

use Phake;

class TypeTest extends \PHPUnit_Framework_TestCase
{
  public function testType()
  {
    $type = new Type('foo');

    $this->assertSame('foo', $type->name());

    $type = new Type('bar');

    $this->assertSame('bar', $type->name());
  }

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

  public function testAccept()
  {
    $type = new Type('foo');
    $visitor = Phake::mock(__NAMESPACE__.'\Visitor');
    $type->accept($visitor);

    Phake::verify($visitor)->visitType(
      $this->identicalTo($type)
    );
  }
}
