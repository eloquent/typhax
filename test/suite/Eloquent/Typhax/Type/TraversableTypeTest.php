<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Type;

use Phake;
use PHPUnit_Framework_TestCase;

class TraversableTypeTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->_primaryType = Phake::mock(__NAMESPACE__.'\TraversablePrimaryType');
        $this->_keyType = Phake::mock(__NAMESPACE__.'\Type');
        $this->_valueType = Phake::mock(__NAMESPACE__.'\Type');

        $this->_type = new TraversableType(
            $this->_primaryType,
            $this->_keyType,
            $this->_valueType
        );
    }

    public function testPrimaryType() {
        $this->assertSame($this->_primaryType, $this->_type->primaryType());
    }

    public function testKeyType() {
        $this->assertSame($this->_keyType, $this->_type->keyType());
    }

    public function testValueType() {
        $this->assertSame($this->_valueType, $this->_type->valueType());
    }

    public function testAccept()
    {
        $visitor = Phake::mock(__NAMESPACE__.'\Visitor');
        $this->_type->accept($visitor);

        Phake::verify($visitor)->visitTraversableType(
            $this->identicalTo($this->_type)
        );
    }
}
