<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Type;

use Phake;
use PHPUnit_Framework_TestCase;

class TraversableTypeTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->primaryType = Phake::mock(__NAMESPACE__.'\TraversablePrimaryType');
        $this->keyType = Phake::mock(__NAMESPACE__.'\Type');
        $this->valueType = Phake::mock(__NAMESPACE__.'\Type');

        $this->type = new TraversableType(
            $this->primaryType,
            $this->keyType,
            $this->valueType
        );
    }

    public function testPrimaryType()
    {
        $this->assertSame($this->primaryType, $this->type->primaryType());
    }

    public function testKeyType()
    {
        $this->assertSame($this->keyType, $this->type->keyType());
    }

    public function testValueType()
    {
        $this->assertSame($this->valueType, $this->type->valueType());
    }
}
