<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\IntrinsicType;

class IntrinsicTypeAliasTest extends \Eloquent\Typhax\Test\TestCase
{
  /**
   * @covers Eloquent\Typhax\IntrinsicType\IntrinsicTypeAlias
   * @group intrinsic-types
   */
  public function testMultiton()
  {
    $expected = array(
      'ALIAS_BOOL' => IntrinsicTypeAlias::ALIAS_BOOL(),
      'ALIAS_CALLABLE' => IntrinsicTypeAlias::ALIAS_CALLABLE(),
      'ALIAS_DOUBLE' => IntrinsicTypeAlias::ALIAS_DOUBLE(),
      'ALIAS_FLOATABLE' => IntrinsicTypeAlias::ALIAS_FLOATABLE(),
      'ALIAS_INT' => IntrinsicTypeAlias::ALIAS_INT(),
      'ALIAS_KEYABLE' => IntrinsicTypeAlias::ALIAS_KEYABLE(),
      'ALIAS_LONG' => IntrinsicTypeAlias::ALIAS_LONG(),
      'ALIAS_REAL' => IntrinsicTypeAlias::ALIAS_REAL(),
    );

    $this->assertSame($expected, IntrinsicTypeAlias::multitonInstances());
  }

  /**
   * @return array
   */
  public function valueData()
  {
    return array(
      array('ALIAS_BOOL', 'bool', 'boolean'),
      array('ALIAS_CALLABLE', 'callable', 'callback'),
      array('ALIAS_DOUBLE', 'double', 'float'),
      array('ALIAS_FLOATABLE', 'floatable', 'numeric'),
      array('ALIAS_INT', 'int', 'integer'),
      array('ALIAS_KEYABLE', 'keyable', 'scalar'),
      array('ALIAS_LONG', 'long', 'integer'),
      array('ALIAS_REAL', 'real', 'float'),
    );
  }

  /**
   * @covers Eloquent\Typhax\IntrinsicType\IntrinsicTypeAlias
   * @dataProvider valueData
   * @group intrinsic-types
   */
  public function testValues($key, $value, $typeName)
  {
    $this->assertSame($value, IntrinsicTypeAlias::instanceByKey($key)->value());
    $this->assertSame($typeName, IntrinsicTypeAlias::instanceByKey($key)->typeName());
  }
}
